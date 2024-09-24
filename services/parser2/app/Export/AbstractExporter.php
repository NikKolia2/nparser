<?php

namespace App\Export;


abstract class AbstractExporter
{
    public function __construct(
        public array $data,
        public ConfigExporter $config
    ){

    }
    
    abstract public function execute():bool;
}
