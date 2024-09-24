<?php

namespace App\Export\Xlsx\Views\Rows;

use App\Export\Xlsx\Views\Rows\Rows;
use App\Export\Xlsx\Views\Columns\Column;
use App\Export\Xlsx\Views\Columns\Columns;


class Row
{
    public function __construct(
        public Columns $columns = new Columns(),
        public readonly ?Rows $rows = null
    ){

    }

    public function addColumn(Column $column, int $position = Columns::INDEX_BEFORE_FIRST_ELEMENT){
        $this->columns->add($column, $position);
    }

    public function setColumn(Column $column, int $position){
        $this->columns->set($column, $position);
    }

    public function removeColumn(int $index){
        $this->columns->remove($index);
    }

    public function bindForCollection(Rows $rows){
        $this->rows = $rows;
    }
}
