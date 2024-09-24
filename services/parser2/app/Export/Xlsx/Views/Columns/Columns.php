<?php

namespace App\Export\Xlsx\Views\Columns;

class Columns
{
    public const INDEX_AFTER_LATST_ELEMENT = -2;
    public const INDEX_BEFORE_FIRST_ELEMENT = -1;

    protected array $columns = [];
    public int $length = 0;

    public function add(Column $column, int $position = self::INDEX_AFTER_LATST_ELEMENT){
        $this->columns[] = $column;
        ++$this->length;
    }

    public function set(Column $column, int $position){
        $this->columns[$position] = $column;
    }

    public function remove(int $index){
        unset($this->columns[$index]);
        array_values($this->columns);
        $this->length = count($this->columns);
    }

    public function item(int $index):Column{
        return $this->columns[$index];
    }

    public static function parse(array $data, int $indexRow = 0):Columns{
        $columns = new Columns();
        
        foreach($data as $indexColumn => $item){
            $item["coords"] = [$indexColumn, $indexRow];
            $columns->add(new Column(...$item));
        }

        return $columns;
    }

    public function toArray(){
        return $this->columns;
    }
}
