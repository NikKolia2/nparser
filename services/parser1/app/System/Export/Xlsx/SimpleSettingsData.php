<?php

namespace App\System\Export\Xlsx;

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
        foreach($columns as $column){
            $row["columns"][] = [
                "value" => $column
            ];
        }

        return $row;
    }   

    public static function create(array $headerColumns, array $bodyColumns){
        $obj = new static($headerColumns, $bodyColumns);
        return $obj->get();
    }
}
