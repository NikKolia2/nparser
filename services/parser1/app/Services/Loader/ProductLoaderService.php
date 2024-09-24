<?php

namespace App\Services\Loader;

use App\VewsDom\ProductView;

class ProductLoaderService extends LoaderService
{
    public function beforeSave(LoaderResponse $response, string $pathDirStorageHTML): bool{
        $html = $response->getHtml();
        $productView = new ProductView($html);
        if($productView->title === "Just a moment..."){
            $this->stack[] = $response->getUrl();
            return false;
        }

        return true;
    }
}
