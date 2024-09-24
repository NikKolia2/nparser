<?php

namespace App\System\Export\Xlsx\Views\Actions;

use App\System\Export\Xlsx\Views\Columns\Column;
use App\System\Export\Xlsx\Views\View;

abstract class AbstractAction
{
    public static $name;

    abstract public function execute(Column $column);

    public static function getView():string{
        return View::class;
    }
}
