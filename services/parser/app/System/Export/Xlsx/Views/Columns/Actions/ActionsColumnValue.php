<?php

namespace App\System\Export\Xlsx\Views\Columns\Actions;

use App\System\Export\Xlsx\Views\Columns\Column;

class ActionsColumnValue
{
    protected array $actions = [];
    public int $length = 0;
    public function add(ActionColumnValue $action){
        $this->actions[] = $action;
        ++$this->length;
    }

    public function getActionsByRunTime(EnumRunTime $runTime):ActionsColumnValue{
        $actions = array_filter($this->actions, function($item) use ($runTime){
            return $item->runTime == $runTime;
        });

        $actionsView = new ActionsColumnValue();
        foreach($actions as $action){
            $actionsView->add($action);
        }

        return $actionsView;
    }

    public static function parse(array $data, Column $column):ActionsColumnValue{
        $actionsView = new ActionsColumnValue();
        foreach($data as $item){
            $actionsView->add(new ActionColumnValue(...$item));
        }

        return $actionsView;
    }

    public function item(int $index):ActionColumnValue{
        return $this->actions[$index];
    }
}


