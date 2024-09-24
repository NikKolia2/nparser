<?php

namespace App\Export\Xlsx\Views;

enum XlsxExporterViews: string
{
    case view = 'view';
    
    public function getClass(){
        return match($this) {
            static::view => View::class,
        };
    }
}
