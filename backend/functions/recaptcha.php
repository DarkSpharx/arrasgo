<?php

/**
 * Vérifie un token reCAPTCHA v3 côté serveur.
 * Retourne ['success'=>bool,'score'=>float,'action'=>string,'raw'=>array]
 */
function verify_recaptcha_v3(string $token, string $action = 'submit', float $min_score = 0.5): array
{
    $config = require __DIR__ . '/../config/recaptcha.php';
    $secret = $config['secret'] ?? '';
    if (!$secret || !$token) {
        return ['success' => false, 'score' => 0.0, 'action' => $action, 'raw' => ['error' => 'missing_secret_or_token']];
    }
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $post = http_build_query(['secret' => $secret, 'response' => $token]);
    // Try file_get_contents() first (lightweight), then fallback to cURL if available
    $resp = null;
    $opts = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => $post,
            'timeout' => 5,
        ]
    ];
    $context = stream_context_create($opts);
    $resp = @file_get_contents($url, false, $context);
    if ($resp === false || $resp === null) {
        // Try cURL fallback
        if (function_exists('curl_version')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            $resp = curl_exec($ch);
            $curlErr = curl_error($ch);
            $curlInfo = curl_getinfo($ch);
            curl_close($ch);
            if ($resp === false || $resp === null) {
                error_log('[recaptcha] curl failed: ' . $curlErr . ' info=' . json_encode($curlInfo));
                return ['success' => false, 'score' => 0.0, 'action' => $action, 'raw' => ['error' => 'no_response_curl', 'curl_err' => $curlErr, 'curl_info' => $curlInfo]];
            }
        } else {
            error_log('[recaptcha] no file_get_contents response and cURL not available');
            return ['success' => false, 'score' => 0.0, 'action' => $action, 'raw' => ['error' => 'no_response_no_curl']];
        }
    }
    $json = json_decode($resp, true);
    $success = !empty($json['success']);
    $score = isset($json['score']) ? floatval($json['score']) : 0.0;
    $respAction = $json['action'] ?? '';
    $accepted = $success && ($score >= $min_score) && ($respAction === $action);
    // Log raw response for debugging (trim to reasonable length)
    if (!empty($json)) {
        error_log('[recaptcha] raw response: ' . substr(json_encode($json), 0, 2000));
    } else {
        error_log('[recaptcha] json decode failed, raw: ' . substr((string)$resp, 0, 2000));
    }
    return ['success' => $accepted, 'score' => $score, 'action' => $respAction, 'raw' => $json];
}
