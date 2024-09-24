<?php

/**
 * массив глобальных настроек системы на основании config/.env файла
 */
if (file_exists(__DIR__ . '/.env')) {
    $environment = '.env';
} else {
    $environment = '.env.example';
}
$envs  =  file(__DIR__ . '/' . $environment);
foreach ($envs as $env) {
    if (strlen($env) > 1) {
        $t = str_replace(array("\r\n", "\r", "\n"), '', $env);
        putenv($t);
    }
}

$globalConfig =  [
    'database' => [
        'host' => getenv('DB_HOST'),
        'port' => getenv('DB_PORT'),
        'name' => getenv('DB_NAME'),
        'user' => getenv('DB_USER'),
        'password' => getenv('DB_PASSWORD'),
        'rootPassword' => getenv('DB_ROOT_PASSWORD'),
        'driver' => getenv('DB_DRIVER'),
        'charset' => getenv('DB_CHARSET')
    ],
];
