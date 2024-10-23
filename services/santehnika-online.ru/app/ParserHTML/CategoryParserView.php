<?php

namespace App\ParserHTML;


use App\VewsDom\CategoryView;
use App\Services\ProcessService;
use App\Models\Category\CategoryModel;

class CategoryParserView extends AbstractParserView
{
    protected CategoryView $viewDom;
    protected CategoryModel $categoryModel;
  
    public function __construct(
        string $pathToFile,
        $process
    ){
        parent::__construct($pathToFile, $process);

        $this->viewDom = new CategoryView($pathToFile, true);
        $this->categoryModel = new CategoryModel();
    }

    public function execute(): bool
    {
        try{
            //Данные процесса
            if(!empty($this->process->data)){
                $processData = json_decode($this->process->data, true);
            }else{
                $processData = [];
            }
           
            
            $columns = $this->categoryModel->getTableColumns();
            $data = [];
            
            foreach($columns as $column){
                if(!empty($value = $this->viewDom->$column))
                    $data[$column] = $value;
            }

            //Корневая категория
            $data["is_main_category"] = 0;
            
            if(isset($processData["category_id"])){
                //Родительская категория
                $data["main_category_id"] = $processData["category_id"];
            }
            
            $catgoryId = $this->categoryModel->createOrUpdate($data);
            $productsUrls = $this->viewDom->getAttributeProductsUrls();

            //Если есть пагинация, то создать процесс на парсинг ссылок пагинации
            if(isset($processData["is_pagination"]) && $processData["is_pagination"] == 1){
                $paginationUrls = $this->viewDom->getAttributePaginationUrls();
                new ProcessService($paginationUrls, ProcessService::TYPE_CATEGORY, $data["h1"], [
                    "category_id" => $catgoryId
                ]);
            }

            //Создать процесс на парсинг ссылок товаров
            new ProcessService($productsUrls, ProcessService::TYPE_PRODUCT, $data["h1"], [
                "category_id" => $catgoryId
            ]);
        }catch(\Throwable $e){
            return false;
        }

        return true;
    }
}
