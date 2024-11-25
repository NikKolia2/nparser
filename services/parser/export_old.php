<?php

ini_set('memory_limit', '10000M');

require('config/bootstrap.php');

use App\Services\HelperService;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

$categories = $pdo->query("SELECT * FROM categories WHERE main_category_id=0")->fetchAll();
$pathToSave = dirname(__DIR__, 2)."/storage/export/xlsx/";
export($categories, $pathToSave);

function export($categories, $pathToSave){
    foreach ($categories as $category) {
        $p = $pathToSave.HelperService::translite($category["h1"]); 
        exportByCateglry($category["id"], $p);
        $c = getChildrenCategories($category["id"]);
        export($c, $p."/");
    }
}

function getChildrenCategories($categoryId){
    global $pdo;
    $categories = $pdo->query("SELECT * FROM categories WHERE main_category_id=$categoryId and is_pag=0")->fetchAll();
    return $categories;
}

function exportByCateglry($categoryId, $pathToSave){
    global $pdo, $globalConfig;
    $category = $pdo->query("SELECT * FROM categories WHERE id='{$categoryId}'")->fetch();
   
    if(!file_exists($pathToSave) && ($category["level"] = 1 || $category["level"] = 2)){
        mkdir($pathToSave, 0777, true);
    }

    $categoriesPag = $pdo->query("SELECT id FROM categories WHERE main_category_id='{$categoryId}' and is_pag=1")->fetchAll();
    $categoriesIds = array_merge([$category["id"]], array_column($categoriesPag, "id"));
    $categoriesIds = implode(",", $categoriesIds);

    $sql = "SELECT * FROM products WHERE category_id in ({$categoriesIds}) and h1 is not null";

    $products = $pdo->query($sql)->fetchAll();
    if(empty($products))
        return 0;

    $productsIds = implode(",", array_column($products, "id"));
   
    echo "Найдено товаров " . count($products);
    echo PHP_EOL;

    $writer = WriterEntityFactory::createXLSXWriter();
    $fileName = "{$globalConfig['database']['name']}_".date("Y-m-d_H-i-s")."_".HelperService::translite($category["h1"])."_products.xlsx";
    $writer->openToFile($pathToSave."/". $fileName);
 

    $headLines = [
        'Код товара', 'Цена', 'Старая цена', 'Наименование', 'Фото', 'Артикул', 'Бренд', 'Характеристики'
    ];

    echo "Получаем опции";
    // получаем массив с уникальными опциями
    $sql = "SELECT DISTINCT option_name, option_key FROM `product_options` WHERE product_id IN (".$productsIds.") ORDER BY option_name ASC";

    $stm = $pdo->prepare($sql);
    $stm->execute([]);
    $optionsList = $stm->fetchAll();

    // продолжим заполнять шапку таблицы названиями опций
    $j = count($headLines) + 1;
    foreach ($optionsList as $option) {
        $headLines[] = $option['option_name'];
        // $tab->setCellValue([$j, 1], $option['option_name']);
        // $j++;
    }

    $rowFromValues = WriterEntityFactory::createRowFromArray($headLines);
    $writer->addRow($rowFromValues);

    // $row = 1;
    foreach ($products as $product) {
        $head = $headLines;
        // начальные координаты в сетке таблицы, соответствуют ячейке A1
        //$column = 1;
        // сдвинемся в таблице на строчку ниже
       // $row++;

        // получаем ссылку на изображение и имя загруженнного файла
        $sql = "SELECT * FROM product_images WHERE product_id=:productId";
        $stm = $pdo->prepare($sql);
        $stm->execute(['productId' => $product['id']]);
        $images = $stm->fetchAll();
        $images = implode(";", array_column($images, 'url'));

        $sql = "SELECT * FROM product_options WHERE product_id=:productId";
        $stm = $pdo->prepare($sql);
        $stm->execute(['productId' => $product['id']]);
        $options = $stm->fetchAll();

        $allOptions = [];
        foreach($options as $option){
            $allOptions[] = $option['option_name'].":".$option['option_value'];
        }

        $allOptions = implode("\n", $allOptions);
        // переформатируем массив с опциями так, чтобы ключи массива стали равны ключам опции
        $optionsWithArraykeys = [];
        foreach ($options as $option) {
            $optionsWithArraykeys[$option['option_key']] = $option;
        }


        // собираем финальный массив с опциями этого товара
        // его длина будет равна максимальному количеству опций в системе
        // таким образом у нас массив опций каждого товара будет иметь одинаковую длинну
        $productOptions = [];
        foreach ($optionsList as $o) {
            if (isset($optionsWithArraykeys[$o['option_key']])) {
                $productOptions[] = $optionsWithArraykeys[$o['option_key']];
            } else {
                $productOptions[] = [];
            }
        }


        // массив данных для одной строки в файле
        // частично заполняем его вручную
        $data = [
            $product['code'], $product['price'], $product["oldPrice"],$product['h1'], $images,
            $product['article'], $product['brand'], $allOptions, 
        ];


        // дописываем в массив данные об опциях автоматически
        foreach ($productOptions as $option) {
            $data[] = !empty($option) ? $option['option_value'] : '';
        }

        $rowFromValues = WriterEntityFactory::createRowFromArray($data);
        $writer->addRow($rowFromValues);
    }

    echo "===============================================";
    echo PHP_EOL;

    $writer->close();
}