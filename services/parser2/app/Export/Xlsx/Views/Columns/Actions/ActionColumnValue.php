<?php

namespace App\Export\Xlsx\Views\Columns\Actions;

use App\Export\Xlsx\Views\Columns\Column;
use App\Export\Xlsx\Views\Actions\AbstractAction;


class ActionColumnValue
{
    protected AbstractAction $action;
    public function __construct(
        public EnumRunTime $runTime,
        string $action,
        protected Column $column
    ){
        $this->action = new $action();
    }

    public function execute(){
        $this->action->execute($this->column);
    }
}
