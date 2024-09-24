<?php

namespace App\System\Export\Xlsx\Views;

use App\System\Export\Xlsx\Views\View;


enum XlsxExporterViews: string
{
    case view = 'view';
    
    public function getClass(){
        return match($this) {
            static::view => View::class,
        };
    }
}
