<?php

namespace App\System\Export\Xlsx\Views\Columns\Actions;

use App\System\Export\Xlsx\Views\Actions\AbstractAction;
use App\System\Export\Xlsx\Views\Columns\Column;


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
