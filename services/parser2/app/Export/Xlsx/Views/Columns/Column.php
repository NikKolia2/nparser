<?php

namespace App\Export\Xlsx\Views\Columns;

use App\Export\Xlsx\Views\Rows\Row;
use App\Export\Xlsx\Views\Columns\Columns;
use App\Export\Xlsx\Views\Columns\Actions\EnumRunTime;
use App\Export\Xlsx\Views\Columns\Actions\ActionsColumnValue;


class Column
{
    public readonly ?Row $row;
    public readonly int $position;
    public ActionsColumnValue $actions;
    public function __construct(
        public int|string|null $value = null,
        ActionsColumnValue|array $actions = new ActionsColumnValue(),
    ){
        $this->row = null;
        $this->position = -1;

        if(is_array($actions)){
            $this->actions = ActionsColumnValue::parse($actions, $this);
        }else{
            $this->actions = $actions;
        }
    }

    public function runActions(EnumRunTime $runTime){
        $actions = $this->actions->getActionsByRunTime($runTime);
        for($i = 0; $i < $actions->length; $i++){
            $action = $this->actions->item($i);
            $action->execute();
        }
    }

    public function bindForRow(Row $row, int $position){
        $this->row->removeColumn($this->position);

        if($position === Columns::INDEX_AFTER_LATST_ELEMENT || $position === Columns::INDEX_BEFORE_FIRST_ELEMENT){
            $row->addColumn($this, $position);
        }else{
            $row->setColumn($this, $position);
        }

        $this->position = $position;
        $this->row = $row;
    }

    // public function getCoords():ColumnCoords{
    //     // new ColumnCoords($this->position)   
    // }
}
