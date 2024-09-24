<?php

namespace App\System\Export\Xlsx\Views\Rows;

use App\System\Export\Xlsx\Views\Rows\Row;
use App\System\Export\Xlsx\Views\Columns\Columns;
use App\System\Export\Xlsx\Views\Columns\Actions\EnumRunTime;


class Rows
{
    protected $rows = [];
    public int $length = 0;

    public function add(Row $row){
        $this->rows[] = $row;
        ++$this->length;
    }

    public function item(int $index):Row{
        return $this->rows[$index];
    }

    public static function parse(array $data):Rows{
        $rows = new Rows();
        
        foreach($data as $indexRow => $item){
            $row = new Row();
            $row->columns = Columns::parse($item["columns"], $indexRow);
            $rows->add($row);
        }

        return $rows;
    }

    public function merge(Rows $rows){
        $this->rows = array_merge($this->rows, $rows->toArray());
        $this->length = count($this->rows);
    }

    public function executeActionsByRunTime(EnumRunTime $runTime){
        for($indexRow = 0; $indexRow < $this->length; $indexRow++){
            $row = $this->item($indexRow);
            $columns = $row->columns;
            for($indexColumn = 0; $indexColumn < $columns->length; $indexColumn++){
                $column = $columns->item($indexColumn);
                $column->runActions($runTime);
            }
        }
    }

    public function toArray(){
        return $this->rows;
    }
}
