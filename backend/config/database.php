<?php

// Helper to read env from multiple sources
function read_env_var(string $key)
{
    // 1. getenv
    $v = getenv($key);
    if ($v !== false) return $v;
    // 2. superglobals
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    // 3. try reading .env (parsed as ini) if present
    $envFile = __DIR__ . '/../../.env';
    if (file_exists($envFile)) {
        $env = @parse_ini_file($envFile);
        if ($env && isset($env[$key]) && $env[$key] !== '') return $env[$key];
    }
    return null;
}

// Collect DB config from multiple sources
$host = read_env_var('DB_HOST');
$dbname = read_env_var('DB_NAME');
$username = read_env_var('DB_USER');
$password = read_env_var('DB_PASS');

// Définit une variable $pdo (null si connexion impossible)
$pdo = null;

// Attempt to connect if we have the basics
if ($host && $dbname && $username) {
    try {
        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // Log detailed diagnostic info (password masked)
        $masked = strlen((string)$password) ? str_repeat('*', min(6, strlen($password))) : '(empty)';
        error_log(sprintf('[database] PDOException connecting to %s/%s as %s (pass=%s): %s', $host, $dbname, $username, $masked, $e->getMessage()));
        $pdo = null;
    }
} else {
    // Log what we did find for diagnostics (mask password)
    $masked = strlen((string)$password) ? str_repeat('*', min(6, strlen($password))) : '(empty)';
    error_log(sprintf('[database] DB config incomplete — DB_HOST=%s DB_NAME=%s DB_USER=%s DB_PASS=%s', var_export($host, true), var_export($dbname, true), var_export($username, true), $masked));
}

// Si la connexion PDO a échoué, fournir un stub léger (NullPDO) pour éviter les erreurs fatales
// lorsque des pages appellent $pdo->query() ou $pdo->prepare() sans vérifier la connexion.
if ($pdo === null) {
    class NullPDOStatement
    {
        public function execute($params = null)
        {
            return false;
        }

        public function fetchAll($mode = null)
        {
            return [];
        }

        public function fetch($mode = null)
        {
            return false;
        }

        public function rowCount()
        {
            return 0;
        }

        public function fetchColumn($col = 0)
        {
            return false;
        }
    }

    class NullPDO
    {
        public function __construct() {}
        public function setAttribute($k, $v) { return true; }
        public function query($sql) { return new NullPDOStatement(); }
        public function prepare($sql) { return new NullPDOStatement(); }
        public function lastInsertId() { return 0; }
        public function errorInfo() { return [null, null, null]; }
        public function beginTransaction() { return false; }
        public function commit() { return false; }
        public function rollBack() { return false; }
    }

    // remplacer $pdo par un stub non-null pour éviter les erreurs "call to a member function on null"
    $pdo = new NullPDO();
    // Indicateur pratique pour détecter que la BDD réelle est indisponible
    $GLOBALS['DB_UNAVAILABLE'] = true;
} else {
    $GLOBALS['DB_UNAVAILABLE'] = false;
}
