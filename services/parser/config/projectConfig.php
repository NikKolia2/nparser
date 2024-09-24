<?php
if (file_exists(__DIR__ . '/.env')) {
    $environment = '.env';
} else {
    die('Не обнаружен локальный .env файл');
}
$envs  =  file(__DIR__ . '/' . $environment);
foreach ($envs as $env) {
    if (strlen($env) > 1) {
        $t = str_replace(array("\r\n", "\r", "\n"), '', $env);
        putenv($t);
    }
}

// подменим название базы данных на ту, что указана в локальном .env
$globalConfig['database']['name'] = getenv('DB_NAME');

// agelar user pdo
$dsn = "mysql:host={$globalConfig['database']['host']};dbname={$globalConfig['database']['name']};charset={$globalConfig['database']['charset']}";

$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo =   new PDO($dsn, $globalConfig['database']['user'], $globalConfig['database']['password'], $opt);
