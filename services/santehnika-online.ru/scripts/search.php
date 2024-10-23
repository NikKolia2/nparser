<?php

use App\VewsDom\CategoryView;
use App\VewsDom\ProductView;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$url = 'https://santehnika-online.ru/product/dozator_art_max_impero_am_1705_cr/283248/';
$filename = hash("sha256", $url);

echo $filename;die;
$view = new ProductView(dirname(__DIR__, 3)."/storage/html/$filename.html", true);


print_r($view->getAttributeBrand());

