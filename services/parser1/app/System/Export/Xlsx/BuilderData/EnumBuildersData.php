<?php

namespace App\System\Export\Xlsx\BuilderData;

enum EnumBuildersData:string
{
    case product = "product";
    public function getClass(){
        return match($this) {
            static::product => ProductBuilderData::class,
        };
    }
}
