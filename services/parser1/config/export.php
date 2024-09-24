<?php

use App\System\Export\Xlsx\SimpleSettingsData;

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
                        'Brand', 'Country', 'информация об упаковке', 'Технические характеристики'
                    ], [
                        "", "h1"
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