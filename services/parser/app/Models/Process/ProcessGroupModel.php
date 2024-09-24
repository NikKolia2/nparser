<?php

namespace App\Models\Process;
use App\Models\Model;
use Override;

class ProcessGroupModel extends Model
{
    protected $table = "process_groups";

    #[Override]
    public static function create($data):?int{
        $processCode = $data["processCode"];
        $urls = $data["urls"];
       
        $model = new static();
        foreach($data['urls'] as $url){
            $query = $model->query()->insertInto($model->table)->set([
                'url' => $url,
                'pid' => $processCode
            ]);

            $stm = $query->execute();
        }

        return 1;
    }

}
