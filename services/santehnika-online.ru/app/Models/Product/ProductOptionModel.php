<?php

namespace App\Models\Product;
use PDO;
use App\Models\Model;

class ProductOptionModel extends Model
{
    protected $table = "product_options";
    
    public static function deleteByProductId(int $productId):int{
        $model = new static();
        $query = $model->query()->delete($model->table);
        $query->where('product_id', $productId);
        $stm = $query->execute();

        return $stm->rowCount();
    }

    public function getDistinctCollection(){
        $query = $this->query()->select(["DISTINCT option_name", "option_key"]);
        $query->from($this->table);
        $query->orderBy(["option_name ASC"]);
       
        return $query->fetchArrays();
    }

    public function getOptionsByProductsIds(array $ids):array{
        $query = $this->query()->from($this->table);
        $query->select(["product_id", "option_key", "option_name", "option_value"]);
        $query->where("product_id", $ids);
        
        return $this->pdo->query($query->getQueryString(true))->fetchAll(PDO::FETCH_GROUP);
    }
}
