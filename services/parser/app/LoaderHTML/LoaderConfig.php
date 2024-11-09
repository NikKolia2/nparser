<?php

namespace App\LoaderHTML;

class LoaderConfig
{
    
    public function __construct(
        public ?int $requestTimeout = null,
        public array $requestHeaders = [],
        public ?string $pathDirStorageHTML = null,
        public ?string $pathToProcessesDir = null,
        public int $limitUrlsInGroup = 300
    ){

    }
}
