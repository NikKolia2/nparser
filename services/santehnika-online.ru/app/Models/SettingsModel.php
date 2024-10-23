<?php

namespace App\Models;

class SettingsModel extends Model
{
    protected $table = "settings";

    public static function updateValue(string $code, $value):bool{
        $model = new static();
        $query = $model->query();

        $query->update($model->table)->set([
            "value" => $value
        ])->where("code", $code);

        $stm = $query->execute();

        return (bool)$stm->rowCount();
    }

    public static function getValue($code){
        $model = new static();
        $query = $model->query();

        $result = $query->select("value")->from($model->table)->where("code", $code)->fetchColumn();

        return $result[0];
    }
}
