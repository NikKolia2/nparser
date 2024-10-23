<?php

namespace App\ParserHTML;

use App\VewsDom\ProductView;
use App\Models\Product\ProductModel;
use App\Models\Product\ProductImagesModel;
use App\Models\Product\ProductOptionModel;

class ProductParserView extends AbstractParserView
{
    protected ProductView $viewDom;
    protected ProductModel $productModel;
    protected ProductOptionModel $productOptionModel;
    protected ProductImagesModel $productImagesModel;
    public function __construct(
        string $pathToFile,
        $process
    ){
        parent::__construct($pathToFile, $process);
        $this->viewDom = new ProductView($pathToFile, true);
        $this->productModel = new ProductModel();
        $this->productOptionModel = new ProductOptionModel();
        $this->productImagesModel = new ProductImagesModel();
    }

    public function execute():bool{
        //Данные процесса
        if(!empty($this->process->data)){
            $processData = json_decode($this->process->data, true);
        }else{
            $processData = [];
        }

        $columns = $this->productModel->getTableColumns();
        $data = [];
        
        if(isset($processData["category_id"])){
            $data["category_id"] = $processData["category_id"];
        }
       
        foreach($columns as $column){
            if(!empty($value = $this->viewDom->$column))
                $data[$column] = $value;
        }

        $options = $this->viewDom->getAttributeCharacteristics();
        $images = $this->viewDom->getAttributeImages();
     
        try{
            $productId = $this->productModel->createOrUpdate($data);
         
            if($productId){
                $newImages = [];
                if(!empty($images)){
                    foreach($images as $imageKey => $image){
                        $newImages[] = [
                            "url" => $image,
                            "product_id" => $productId
                        ];
                    }
                    $this->productImagesModel->deleteByProductId($productId);
                    $this->productImagesModel->createMany($newImages);
                }

                if(!empty($options)){
                    foreach($options as $optionKey => $option){
                        $options[$optionKey]["product_id"] = $productId;
                    }
                    $this->productOptionModel->deleteByProductId($productId);
                    $this->productOptionModel->createMany($options);
                }
            }else{
              
                return false;
            }
        }catch(\Throwable $e){
            echo $e->getMessage().PHP_EOL;
            return false;
        }
      
        return true;
    }
}
