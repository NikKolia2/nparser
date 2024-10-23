<?php

namespace App\Services\Loader;

class LoaderResponse
{
    public $url;
    public $html;
    protected int $status = 200;

    public function __construct(string $url, ?string $html = "", protected array $config = []){
        $this->url = $url;
        $this->html = $html;
        $this->initConfig();
    }

    protected function initConfig():void{
        if(isset($this->config["status"])){
            $this->status = $this->config["status"];
        }    
    }

    public function getHashUrl():string{
        return hash('sha256', $this->url);
    }

    public function getStatus(){
        return $this->status;
    }

    public function getHtml():string{
        return $this->html;
    }

    public function getUrl():string{
        return $this->url;
    }
}
