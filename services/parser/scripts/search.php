<?php

use App\VewsDom\ProductView;

require('config/bootstrap.php');

$url = "https://www.vseinstrumenti.ru/search_main.php?what=15084354";
$filename = hash("sha256", $url);

$view = new ProductView(dirname(__DIR__, 3)."/storage/html/$filename.html", true);


echo $view->getAttributeWarehouse();

