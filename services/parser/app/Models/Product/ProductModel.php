<?php

namespace App\Models\Product;

use App\Models\Model;
use App\Models\Process\ProcessGroupModel;
use App\Models\Process\ProcessModel;

class ProductModel extends Model
{
    protected $table = "products";

    public static function createOrUpdate(array $data):?int{
        $model = new static();
        $query = $model->query()->insertInto($model->table)->set($data);

        $rows = [];
        foreach($data as $key => $item){
            $rows[] = "`$key`=:$key";
        }

        $st = implode(",", $rows);

        $query->onDuplicateKeyUpdate($st)->setVars($data);

        $stm = $query->execute();
        if($stm->rowCount() && ($id = (int)$query->getLastInsertId()) > 0){
            return $id;
        }else{
            $result = $model->query()->select('id')->from($model->table)->where("h1", $data['h1'])->fetchColumn();
            return $result[0];
        }
    }

    public function getProductsByGroupProcess(?string $pid = null){
        $query = $this->query()->select()->from($this->getTableName());
        
        if($pid){
            $query->where("url IN (". $this->query()->select("url")->from(ProcessGroupModel::getTableName())->where("pid", $pid)->getQueryString(true).")");
        }

        return $query->fetchArrays();
    }
}
