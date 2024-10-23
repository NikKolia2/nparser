<?php

namespace App\System\Export\Xlsx\Views\Actions;

use App\System\Export\Xlsx\Views\Columns\Column;
use App\System\Export\Xlsx\Views\View;

abstract class AbstractAction
{
    public static $name;

    public function execute($value):mixed {
        if($value instanceof Column){
            $value = $value->value;
        }

        if($value instanceof AbstractAction || $value instanceof ExecuteAction){
            $value = $value->execute();
        }

        return $value;
    }

    public static function getView():string{
        return View::class;
    }

    public static function init($value):ExecuteAction{
        $action = new static();
        return new ExecuteAction($action, $value);
    }
}
