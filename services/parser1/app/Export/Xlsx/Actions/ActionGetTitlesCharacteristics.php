<?php

namespace App\Export\Xlsx\Actions;
use App\Models\Product\ProductOptionModel;
use App\System\Export\Xlsx\Views\Columns\Column;
use App\System\Export\Xlsx\Views\Actions\AbstractAction;

class ActionGetTitlesCharacteristics extends AbstractAction
{
    public static $name = "getTitlesCharacteristics";
    public function execute(Column $column){
        $optionModel = new ProductOptionModel();
        $options = $optionModel->getDistinctCollection();

        $newColumns = [];
        foreach($options as $option){
            $newColumns[] = new Column($option["option_key"]);
        }       

        $position = $column->position;
        $row =  $column->row;
        $row->removeColumn($position);

        foreach($newColumns as $column){
            $row->addColumn($column, $position);
            $position++;
        }
    }
}
