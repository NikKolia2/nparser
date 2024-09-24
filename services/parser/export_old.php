<?php

ini_set('memory_limit', '10000M');

require('config/bootstrap.php');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// $spreadsheet = new Spreadsheet();
// $activeWorksheet = $spreadsheet->getActiveSheet();
// $activeWorksheet->setCellValue('A1', 'Hello World !');

// $writer = new Xlsx($spreadsheet);
// $writer->save('hello world.xlsx');

// $sql = "SELECT * FROM categories WHERE parent_id is null";
// $stm = $pdo->prepare($sql);
// $stm->execute([]);
// $categories = $stm->fetchAll();

// print_r(count($categories));

$spreadsheet = new Spreadsheet();
$categoryItteration = 0;
//foreach ($categories as $category) {
    // CREATE A NEW SPREADSHEET + POPULATE DATA
    // $sheet = $spreadsheet->getActiveSheet();
    // $sheet->setTitle('Batch');
    // Add some data
    $spreadsheet->createSheet();
    // Add some data
    $spreadsheet->setActiveSheetIndex($categoryItteration);
    $tab = $spreadsheet->getActiveSheet();
    $tab->setTitle('Результат парсинга');

    $sql = "SELECT * FROM products";
  //  $stm->execute([]);
    $products = $pdo->query($sql)->fetchAll();
    echo "Найдено товаров " . count($products);
    echo PHP_EOL;



    $headLines = [
        'code', 'h1', 'Фото', 'Path', 'Description', 'Complectation', 'Keywords',
        'Brand', 'Country', 'информация об упаковке', 'Технические характеристики'
    ];

    $i = 1;
    foreach ($headLines as $headLine) {
        $tab->setCellValueByColumnAndRow($i, 1, $headLine);
        $i++;
    }

    // получаем массив с уникальными опциями
    $sql = "SELECT DISTINCT option_name, option_key FROM `product_options` ORDER BY option_name ASC";
    $stm = $pdo->prepare($sql);
    $stm->execute([]);
    $optionsList = $stm->fetchAll();

    // продолжим заполнять шапку таблицы названиями опций
    $j = count($headLines) + 1;
    foreach ($optionsList as $option) {
        $tab->setCellValueByColumnAndRow($j, 1, $option['option_name']);
        $j++;
    }



    $row = 1;
    foreach ($products as $product) {
        // начальные координаты в сетке таблицы, соответствуют ячейке A1
        $column = 1;
        // сдвинемся в таблице на строчку ниже
        $row++;

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
            $product['article'], $product['h1'], $images, $product['breadcrumbs'],
            $product['description'], $product['complectation'], $product['keywords'], $product['brand'], $product['country'], $product['infoPack'], $allOptions
        ];


        // дописываем в массив данные об опциях автоматически
        foreach ($productOptions as $option) {
            $data[] = !empty($option) ? $option['option_value'] : '';
        }

        // $columnTemp = 1;
        foreach ($data as $d) {
            $tab->setCellValueByColumnAndRow($column, $row, $d);
            $column++;
        }
    }
    echo "===============================================";
    echo PHP_EOL;

    $categoryItteration++;
//}

$fileName = "{$globalConfig['database']['name']}_".date("Y-m-d_H-i-s")."_products.xlsx";
$writer = new Xlsx($spreadsheet);
$writer->save(dirname(__DIR__, 2)."/storage/export/xlsx/" . $fileName);