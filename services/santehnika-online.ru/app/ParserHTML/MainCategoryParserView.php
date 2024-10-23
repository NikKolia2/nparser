<?php

namespace App\ParserHTML;

use App\VewsDom\CategoryView;

use App\Services\ProcessService;
use App\Models\Category\CategoryModel;

class MainCategoryParserView extends AbstractParserView
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
            $data["h1"] = $this->viewDom->getAttributeH1();
            $data["is_main_category"] = 1;
            $data["main_category_id"] = 0;

            $catgoryId = $this->categoryModel->createOrUpdate($data);
            $urls = $this->viewDom->getAttributeCategoriesUrls();

            if(!empty($urls)){
                new ProcessService($urls, ProcessService::TYPE_CATEGORY, $data["h1"], [
                    "category_id" => $catgoryId,
                    "is_pagination" =>1
                ]);
            }else{
                //Если есть пагинация, то создать процесс на парсинг ссылок пагинации
                $paginationUrls = $this->viewDom->getAttributePaginationUrls();
                if(!empty($paginationUrls)){
                    new ProcessService($paginationUrls, ProcessService::TYPE_CATEGORY, $data["h1"], [
                        "category_id" => $catgoryId
                    ]);
                }
                
                $productsUrls = $this->viewDom->getAttributeProductsUrls();
                //Создать процесс на парсинг ссылок товаров
                new ProcessService($productsUrls, ProcessService::TYPE_PRODUCT, $data["h1"], [
                    "category_id" => $catgoryId
                ]);
            }
            
        }catch(\Throwable $e){
            echo $e->getMessage().PHP_EOL;
            return false;
        }

        return true;
    }
}
