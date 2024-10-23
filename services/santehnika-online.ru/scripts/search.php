<?php

use App\VewsDom\CategoryView;
use App\VewsDom\ProductView;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$url = 'https://santehnika-online.ru/product/adapter_dlya_izmelchitelya_milacio_mc_033_gm_voronenaya_stal/600678/';
$filename = hash("sha256", $url);

$view = new ProductView(dirname(__DIR__, 3)."/storage/html/$filename.html", true);


print_r($view->getAttributeBrand());

