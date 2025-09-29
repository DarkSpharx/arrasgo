<?php
// Configuration reCAPTCHA. Lire les clés depuis les variables d'environnement si présentes,
// sinon essayer de lire un fichier .env à la racine du projet (format simple KEY=VALUE).
$get_env = function($k) {
    $v = getenv($k);
    if ($v !== false && $v !== '') return $v;
    if (isset($_ENV[$k]) && $_ENV[$k] !== '') return $_ENV[$k];
    if (isset($_SERVER[$k]) && $_SERVER[$k] !== '') return $_SERVER[$k];
    // try .env
    $envFile = __DIR__ . '/../../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($key, $val) = explode('=', $line, 2);
                $key = trim($key);
                $val = trim($val, " \t\n\r\0\x0B\"'");
                if ($key === $k) return $val;
            }
        }
    }
    return '';
};

return [
    'site_key' => $get_env('RECAPTCHA_SITE_KEY'),
    'secret' => $get_env('RECAPTCHA_SECRET'),
    // seuil minimal pour accepter (0.0 - 1.0)
    'min_score' => floatval($get_env('RECAPTCHA_MIN_SCORE') ?: 0.5),
];
