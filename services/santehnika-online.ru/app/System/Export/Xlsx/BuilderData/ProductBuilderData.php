<?php

namespace App\System\Export\Xlsx\BuilderData;

use App\Models\Product\ProductImagesModel;
use App\Models\Product\ProductModel;
use App\Models\Product\ProductOptionModel;

class ProductBuilderData extends AbstractBuilderData
{
    public function setImages(array &$products){
        $model = new ProductImagesModel();
        $productsIds = array_column($products, "id");
        $images = $model->getImagesByProductsIds($productsIds);
    
        foreach($products as $key => $product){
            if(isset($images[$product["id"]])){
                $i =  $images[$product["id"]];
                $i = implode(";", array_column($i, "url"));
                $products[$key]["images"] = $i;
            }
        }
    }

    public function setOptions(array &$products){
        $model = new ProductOptionModel();
        $productsIds = array_column($products, "id");
        $options = $model->getOptionsByProductsIds($productsIds);
    
        foreach($products as $key => $product){
            if(isset($options[$product["id"]])){
                $optionsList =  $options[$product["id"]];
                foreach ($optionsList as $o) {
                    $products[$key][$o['option_key']] = $o['option_value'];
                }
            }
        }
    }

    public function setFieldAllOptions(array &$products){
        $model = new ProductOptionModel();
        $productsIds = array_column($products, "id");
        $options = $model->getOptionsByProductsIds($productsIds);
    
        foreach($products as $key => $product){
          
            if(isset($options[$product["id"]])){
                $optionsList =  $options[$product["id"]];
              
                $allOptions = [];
                foreach($optionsList as $option){
                    $allOptions[] = $option['option_name'].":".$option['option_value'];
                }

                $allOptions = implode("\n", $allOptions);

                $products[$key]["allOptions"] = $allOptions;
            }
        }
    }

    public function execute(int $limitQuery = -1): array {
        $productModel = new ProductModel();
        $products = $productModel->getProductsByGroupProcess();

        $this->setImages($products);
        $this->setFieldAllOptions($products);
        $this->setOptions($products);

        return $products;
    }
}
