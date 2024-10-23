<?php

namespace App\Services\Loader;

use Exception;

class LoaderService
{
    protected int $currentIteration = -1;
    protected $data = [];
    protected $stack = [];
    protected ?LoaderResponse $loaderResponse = null;
    protected string $pathDirStorageHTML = "";
    protected string $pathDirStorageSite = "";
    protected string $pathDirStorageSiteHTML = "";
    protected bool $parseAll = false;

    public function __construct(protected LoaderClient $loaderClient, string|array $data, protected array $config = [])
    {
        if (is_string($data)) {
            $data = file($data, FILE_IGNORE_NEW_LINES);
        }

        if (!count($data)) {
            throw new Exception('Не найдено ни одной ссылки. Добавьте хотя бы одну ссылку.');
        }

        $this->data = $data;
        $this->stack = $data;
        $this->initConfig();
    }

    protected function initConfig()
    {
        if (isset($this->config['pathDirStorageHTML'])) {
            $this->pathDirStorageHTML = $this->config['pathDirStorageHTML'];
        }

        if (isset($this->config['pathDirStorageSite'])) {
            $this->pathDirStorageSite = $this->config['pathDirStorageSite'];
        }

        if (isset($this->config['pathDirStorageSiteHTML'])) {
            $this->pathDirStorageSiteHTML = $this->config['pathDirStorageSiteHTML'];
        }

        if (isset($this->config['parseAll'])) {
            $this->parseAll = (bool)$this->config['parseAll'];
        }
    }

    public function loadNext(): ?LoaderResponse
    {
        echo 3;
        $iteration = $this->currentIteration + 1;
     
        if ($iteration <= count($this->stack) - 1) {
            $this->currentIteration = $iteration;
            $this->loaderResponse = $this->load($this->currentIteration, $this->parseAll, $this->stack);
            return $this->loaderResponse;
        }
 
        return null;
    }

    public function load(int $iteration, $parseAll = false, ?array $data = null): LoaderResponse
    {
        if(!$data){
            $data = $this->data;
        }

        $loaderData = $data[$iteration];

        if(!$parseAll && $this->fileExists($loaderData['url'])){
            return $this->getFileByUrl($loaderData['url']);
        }else {            
            return $this->loadFromInternt($loaderData);
        }
    }

    public function loadFromInternt(array $loaderData):LoaderResponse{
        $html = $this->loaderClient->getHTML($loaderData);

        return new LoaderResponse($loaderData['url'], $html);
    }

    public function getFileByUrl(string $url): LoaderResponse
    {
        $response = new LoaderResponse($url);
        $html = file_get_contents($this->getStoragePathToSaveHTML() . $response->getHashUrl() . ".html");
        $response->html = $html;
        return $response;
    }

    public function getCurrent()
    {
        return $this->loaderResponse;
    }

    public function fileExists(string $url): bool
    {
        $response = new LoaderResponse($url);
        return file_exists($this->getStoragePathToSaveHTML() . $response->getHashUrl() . ".html");
    }

    protected function getStoragePathToSaveHTML()
    {
        return $this->pathDirStorageHTML . $this->pathDirStorageSite . $this->pathDirStorageSiteHTML;
    }

    public function beforeSave(LoaderResponse $response, string $pathStorageHTML):bool{
        return true;
    }

    public function afterSave(LoaderResponse $response, string $pathStorageHTML):void{
        usleep(500000);
    }

    public function save(LoaderResponse $response, $overwrite = false): string|bool
    {
        $storagePath = $this->getStoragePathToSaveHTML();
        $pathStorageHTML = $storagePath . $response->getHashUrl() . ".html";

        if(!$this->fileExists($pathStorageHTML) || $overwrite){
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            
          
            if (file_put_contents($pathStorageHTML, $response->html) !== false) {
                if(!$this->beforeSave($response, $pathStorageHTML)){
                    unlink($pathStorageHTML);
                    return false;
                }
        
                $this->afterSave($response, $pathStorageHTML);
                return $pathStorageHTML;
            }

            return false;
        }else{
            return $pathStorageHTML;
        }
    }

    public function loadAll(): array{
        $result = [];
       
        while($response = $this->loadNext()){
            echo 4;
            if($path = $this->save($response)){
                $result[$response->url] = $path;
            }
        }

        $this->resetStack();

        return $result;
    }

    public function resetStack():void{
        $this->currentIteration = 0;
        $this->stack = $this->data;
    }

    public function getData(){
        return $this->data;
    }
}
