<?php

namespace App\Services;

use App\Models\Process\ProcessModel;

class ProcessService
{
    public readonly string $code;
    public static array $types = [
        "product" => 1,
        "main_category" => 2,
        "category" => 3
    ];

    public const TYPE_PRODUCT = 'product'; 
    public const TYPE_MAIN_CATEGORY = 'main_category';
    public const TYPE_CATEGORY = 'category';

    public function __construct(
        public readonly array $urls,
        public readonly string $type,
        public readonly ?string $name = null,
        public readonly array $data = []
    ){
        $this->code = hash("sha256", implode("", $urls));
        
        ProcessModel::create([
            "processName" => $this->name,
            "processCode" => $this->code,
            "urls" => $urls,
            "type_id" => static::$types[$this->type],
            "created_at" => date("Y-m-d H:i:s"),
            "data" => empty($this->data)? []: $this->data
        ]);
    }
}
