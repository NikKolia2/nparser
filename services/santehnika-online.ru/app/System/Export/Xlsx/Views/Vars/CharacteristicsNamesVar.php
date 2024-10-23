<?php

namespace App\System\Export\Xlsx\Views\Vars;

use App\Models\Product\ProductOptionModel;

class CharacteristicsNamesVar extends AbstractVar
{
    public static $name = "characteristics_names";

    public function execute(){
        $optionModel = new ProductOptionModel();
        $options = $optionModel->getDistinctCollection();

        $newColumns = [];
        foreach($options as $option){
            $newColumns[] = $option["option_name"];
        }       

        return $newColumns;
    }
}
