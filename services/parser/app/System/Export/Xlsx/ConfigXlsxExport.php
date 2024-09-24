<?php

namespace App\System\Export\Xlsx;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConfigXlsxExport
{
   
    public function __construct(
        public readonly string $storagePath,
        public readonly string $actionsPath,
        public readonly string $ext = "xlsx",
        public readonly array $views = [],
        public readonly array $bildersData = [],
    ){
      
    }

    public function getSystemActionPath():string{
        return "";
    }
}