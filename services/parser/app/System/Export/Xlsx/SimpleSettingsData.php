<?php

namespace App\System\Export\Xlsx;
use App\Export\Xlsx\Views\Actions\AbstractAction;
use App\System\Export\Xlsx\Views\Actions\ExecuteAction;

class SimpleSettingsData
{
    public function __construct(
        public array $headerColumns,
        public array $bodyColumns
    ){
        
    }

    public function get():array{
        $header = ["rows" => []];
        $body = ["rows" => []];
        
        $header["rows"][] = $this->createRow($this->headerColumns);
        $body["rows"][] = $this->createRow($this->bodyColumns);

        return [
            "header" => $header,
            "body" => $body
        ];
    }

    protected function createRow(array $columns):array{
        $row = ["columns" => []];
       
        $isColumn = true;
        $iteration = -1;
        while($isColumn){
            $i = $iteration + 1;
            if(isset($columns[$i])){
                $column = $columns[$i];
                if($column instanceof ExecuteAction){
                    $action = $column;
                    unset($columns[$i]);
                    $columns = array_values($columns);
                   
                    $columns = array_merge($columns, $action->execute());
                    $column = $columns[$i];
                }

                $row["columns"][] = [
                    "value" => $column
                ];

                $iteration = $i;
            }else{
                $isColumn = false;
            }
        }

        return $row;
    }   

    public static function create(array $headerColumns, array $bodyColumns){
        $obj = new static($headerColumns, $bodyColumns);
        return $obj->get();
    }
}
