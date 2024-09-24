<?php

namespace App\System\Export;

class ConfigExporter
{
    public readonly string $storagePath;
    public function __construct(
        string $storagePath
    ){
        $this->storagePath = $storagePath;
    }
}
