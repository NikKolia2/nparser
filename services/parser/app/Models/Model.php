<?php

namespace App\Models;

use HemiFrame\Lib\SQLBuilder\Query;

class Model
{
    protected $table;
    public $pdo;
    
    public function __construct(){
        require dirname(__DIR__, 2)."/config/bootstrap.php";
        $this->pdo = $pdo;
    }

    /**
     *  Построитель запросов
     *  https://github.com/heminei/php-query-builder
     */
    public function query()
    {
        return new Query(["pdo" => $this->pdo]);
    }


    public static function create($data):int|null{
        $model = new static();
        $query = $model->query()->insertInto($model->table)->set($data);
      
        $stm = $query->execute();
        if($stm->rowCount()){
            return (int)$query->getLastInsertId();
        }

        return null;
    }

    public static function createMany(array $data):int{
        if(!empty($data)){
            $model = new static();
            $query = $model->query()->insertInto($model->table)->values(array_keys($data[0]),$data);
            $stm = $query->execute();

            return $stm->rowCount();
        }

        return 0;
    }

   

    public static function getTableName():string{
        $model = new static();
        return $model->table;
    }


    public function getTableColumns($table = "")
    {
        if (empty($table))
            $table = static::getTableName();

        return $this->query()->select("column_name")
            ->from("information_schema.columns")
            ->where("table_schema = DATABASE()")
            ->andWhere("table_name", $table)->fetchColumn();
    }
}
