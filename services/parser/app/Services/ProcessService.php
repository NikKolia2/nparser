<?php

namespace App\Services;

use App\Models\Process\ProcessModel;

class ProcessService
{
    public readonly string $code;
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
        
        ProcessModel::create([
            "processName" => $this->name,
            "processCode" => $this->code,
            "urls" => $urls,
            "type_id" => static::$types[$this->type],
            "created_at" => date("Y-m-d H:i:s")
        ]);
    }
}
