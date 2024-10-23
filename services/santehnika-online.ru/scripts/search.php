<?php

use App\VewsDom\CategoryView;
use App\VewsDom\ProductView;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$url = 'https://santehnika-online.ru/product/divan_mebel_ars_grand_120kh200_velyur_goluboy_luna_089/729220/';
$filename = hash("sha256", $url);

echo $filename;die;
$view = new ProductView(dirname(__DIR__, 3)."/storage/html/$filename.html", true);


print_r($view->getAttributeBrand());

