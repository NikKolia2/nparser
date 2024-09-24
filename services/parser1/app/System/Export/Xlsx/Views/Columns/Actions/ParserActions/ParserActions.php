<?php

namespace App\System\Export\Xlsx\Views\Columns\Actions\ParserActions;

use App\System\Export\Xlsx\Views\Actions\ActionsCollection;
use App\System\Export\Xlsx\Views\Columns\Actions\ActionsColumnValue;



class ParserActions
{
    public static function parse(string $value, ActionsCollection $actions){
        
        $arrayActions = $actions->toArray();
    } 
}
