<?php

use App\Models\Process\ProcessModel;
use App\Models\Product\ProductModel;
use App\Services\Loader\LoaderResponse;

require('../config/bootstrap.php');

$processModel = new ProcessModel();
$productModel = new ProcessModel();

$products = $productModel->query()
->select()
->from(ProductModel::getTableName())
->where("h1", null)
->orWhere("article", null)
->fetchArrays();

foreach($products as $product){
    if(empty($product["url"]))
        continue
    $html = new LoaderResponse($product["url"]);
    $filename = $html->getHashUrl();
    unlink(dirname(__DIR__, 3)."/storage/html/$filename.html");
    $productModel->query()->delete(ProductModel::getTableName())->where("id", $product["id"])->execute();
    $processModel->query()->update(ProcessModel::getTableName())->where("url", $product["url"])->set([
        "status_id" => 1
    ])->execute();
}