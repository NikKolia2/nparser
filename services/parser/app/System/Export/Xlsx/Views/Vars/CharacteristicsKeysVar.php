<?php

namespace App\System\Export\Xlsx\Views\Vars;

use App\Models\Product\ProductOptionModel;

class CharacteristicsKeysVar extends AbstractVar
{
    public static $name = "characteristics_keys";
    public function execute(){
        $optionModel = new ProductOptionModel();
        $options = $optionModel->getDistinctCollection();

        $newColumns = [];
        foreach($options as $option){
            $newColumns[] = $option["option_key"];
        }       

        return $newColumns;
    }
}
