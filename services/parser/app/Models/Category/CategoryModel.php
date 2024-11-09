<?php
namespace App\Models\Category;


use App\Models\Model;

class CategoryModel extends Model
{
    protected $table = "categories";

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
}