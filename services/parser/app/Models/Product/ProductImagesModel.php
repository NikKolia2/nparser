<?php

namespace App\Models\Product;
use App\Models\Model;

class ProductImagesModel extends Model
{
    protected $table = "product_images";

    public static function deleteByProductId(int $productId):int{
        $model = new static();
        $query = $model->query()->delete($model->table);
        $query->where('product_id', $productId);
        $stm = $query->execute();

        return $stm->rowCount();
    }
}
