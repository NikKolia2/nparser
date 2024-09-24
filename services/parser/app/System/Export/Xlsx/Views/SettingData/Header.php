<?php

namespace App\System\Export\Xlsx\Views\SettingData;

use App\System\Export\Xlsx\Views\Rows\Rows;


class Header
{   
    public Rows $rows;
    public function __construct(
        Rows|array $rows
    ){
        if(is_array($rows)){
            $this->rows = Rows::parse($rows);
        }else{
            $this->rows= $rows;
        }
    }

    public function getRowsAfterExecuteActions():Rows{
        $rows = new Rows();

        return $rows;
    }
}
