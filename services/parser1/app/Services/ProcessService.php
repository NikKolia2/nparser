<?php

namespace App\Services;

use App\Models\Process\ProcessGroupModel;
use App\Models\Process\ProcessModel;
use App\Models\Process\ProcessNameModel;

class ProcessService
{
    public string $code;
    public static array $types = [
        "product" => 1
    ];

    public const TYPE_PRODUCT = 'product'; 

    public function __construct(
        public readonly array $urls,
        public readonly string $type,
        public readonly ?string $name = null
    ){
        $this->code = hash("sha256", implode("", $urls));

        $processName = new ProcessNameModel();
        $processName->pid = $this->code;
        $processName->name = $this->name;
        $processName->save();

        $this->createTasksProcess();
        $this->createGroupProcess();
    }

    protected function createTasksProcess(){
        $dataForCreate = [];
        $now = date("Y-m-d H:i:s");
        foreach($this->urls as $url){
            $dataForCreate[] = [
                "url" => $url,
                'type_id' => static::$types[$this->type],
                'created_at' => $now
            ];
        }

        ProcessModel::updateOrCreate($dataForCreate);
    }

    protected function createGroupProcess(){
        $dataForCreate = [];
        foreach($this->urls as $url){
            $dataForCreate[] = [
                "url" => $url,
                "pid" => $this->code
            ];
        }

        ProcessGroupModel::updateOrCreate($dataForCreate);
    }
}
