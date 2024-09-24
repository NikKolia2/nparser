<?php
ini_set('memory_limit', '10000M');

require('config/bootstrap.php');

$sql = "SELECT * FROM products";
$stm = $pdo->prepare($sql);
$stm->execute();
$products = $stm->fetchAll();

$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
); 

$storagePath = dirname(__DIR__, 2)."/storage/export/images/by_article/";
foreach($products as $product){
    echo $product["id"].PHP_EOL;
    $dir = $storagePath.$product["article"];
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

   
    $iSql = "SELECT * FROM product_images WHERE product_id=:productId";
    $stm1 = $pdo->prepare($iSql);
    $stm1->execute([
        "productId" => $product["id"]
    ]);

    $images = $stm1->fetchAll();

    foreach($images as $key => $item){
        $filename = basename($item["url"]);
        $parts = explode(".", $filename);
        $ext = $parts[1];
        $newFilename = ($key + 1).".".$ext;
        $pathToFile = $dir."/".$newFilename;

        if(!file_exists($pathToFile)){
            file_put_contents($pathToFile, file_get_contents($item["url"], false, stream_context_create($arrContextOptions)));
        }
    }
}