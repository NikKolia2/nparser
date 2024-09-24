<?php

namespace App\System\Export\Xlsx\BuilderData;

use App\Models\Product\ProductModel;

class ProductBuilderData extends AbstractBuilderData
{

    public function execute(int $limitQuery = -1): array {
        $productModel = new ProductModel();
        $products = $productModel->getProductsByGroupProcess();

        return $products;
    }
}
