<?php

namespace App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
}
