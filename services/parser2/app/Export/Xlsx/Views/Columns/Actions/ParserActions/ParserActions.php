<?php

namespace App\Export\Xlsx\Views\Columns\Actions\ParserActions;

use App\Export\Xlsx\Views\Actions\ActionsCollection;

class ParserActions
{
    public static function parse(string $value, ActionsCollection $actions){
        
        $arrayActions = $actions->toArray();
    } 
}
