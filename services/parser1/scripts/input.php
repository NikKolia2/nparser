<?php
require('config/bootstrap.php');

use App\Services\ProcessService;

$urls = file('links.txt', FILE_IGNORE_NEW_LINES);

if (!count($urls)) {
    echo 'В файле links.txt не найдено ни одной ссылки. Добавьте хотя бы одну ссылку.';
    die;
}

new ProcessService($urls, ProcessService::TYPE_PRODUCT, "Тест парсинга");