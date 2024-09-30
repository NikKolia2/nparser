<?php

namespace App\Models\Product;
use PDO;
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

    public function getImagesByProductsIds(array $ids):array{
        $query = $this->query()->from($this->table);
        $query->select(["product_id", "url"]);
        $query->where("product_id", $ids);
        
        return $this->pdo->query($query->getQueryString(true))->fetchAll(PDO::FETCH_GROUP);
    }
}
