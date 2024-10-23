<?php

namespace App\Export;

abstract class AbstractExport
{
    abstract public function execute(string $filename, array $data):bool;
}
