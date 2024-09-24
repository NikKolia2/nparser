<?php

namespace App\Export\Xlsx\Views\Actions;

use App\Export\Xlsx\Views\View;
use App\Export\Xlsx\Views\Columns\Column;



abstract class AbstractAction
{
    public static $name;

    abstract public function execute(Column $column);

    public static function getView():string{
        return View::class;
    }
}
