<?php

namespace App\Export\Xlsx\Views\Columns;

class ColumnCoords
{
    public function __construct(
        public int $x,
        public int $y
    ){

    }
}
