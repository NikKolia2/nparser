<?php

namespace App\ParserHTML;

use App\Services\Loader\LoaderResponse;

class ParserConfig
{
    
    public function __construct(
        public int $limitProcessInGroup = 300,
        public readonly string $storageHTML = ""
    ){

    }

    public function getPathFileByUrl(string $url){
        $response = new LoaderResponse($url);
        return $this->storageHTML.$response->getHashUrl().".html";
    }
}
