<?php

namespace App\System\Export\Xlsx;

use App\System\Export\ConfigExporter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ConfigXlsxExport extends ConfigExporter
{
   
    public function __construct(
        string $storagePath,
        public readonly string $ext = "xlsx",
        public readonly array $views = [],
        public readonly array $bildersData = [],
    ){
      parent::__construct($storagePath);
    }

    public function getSystemActionPath():string{
        return "";
    }
}