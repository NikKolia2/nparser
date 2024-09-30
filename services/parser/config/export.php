<?php


use App\System\Export\Xlsx\SimpleSettingsData;
use App\System\Export\Xlsx\Views\Actions\ActionGetVar;
use App\System\Export\Xlsx\Views\Vars\CharacteristicsKeysVar;
use App\System\Export\Xlsx\Views\Vars\CharacteristicsNamesVar;

return [
    "xlsx" => [
        "exporter" =>  \App\System\Export\Xlsx\XlsxExporter::class,
        "config" => [
            "storagePath" => dirname(__DIR__, 3)."/storage/export/xlsx/",
            "views" => [

            ],
            "bildersData" => [
                
            ],
        ],
        "data" => [
            [
                "view" => "view",
                "data" => [
                    "data" => SimpleSettingsData::create([
                        'code', 'h1', 'Фото', 'Path', 'Description', 'Complectation', 'Keywords',
                        'Brand', 'Country', 'информация об упаковке', 'Технические характеристики', 
                        ActionGetVar::init(CharacteristicsNamesVar::$name),
                    ], [
                        "article", "h1", "images", "breadcrumbs", "description", 'complectation', 'keywords', 
                        'brand', 'country', 'infoPack','allOptions',  
                        ActionGetVar::init(CharacteristicsKeysVar::$name)
                    ]),
                    
                    "config" => [
                        "name" => 'Тест',
                        "builderData" => "product",
                        "limitQuery" => -1,
                    ]
                ]
            ]
        ]
    ]
];