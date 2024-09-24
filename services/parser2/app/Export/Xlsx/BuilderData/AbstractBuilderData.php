<?php

namespace App\Export\Xlsx\BuilderData;

abstract class AbstractBuilderData
{
    abstract public function execute(int $limitQuery):array;
}
