<?php

namespace App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ProductImagesModel extends Model
{
    protected $table = "product_images";

    public static function deleteByProductId(int $productId):?bool{
        return static::where("product_id", $productId)->delete();
    }
}
