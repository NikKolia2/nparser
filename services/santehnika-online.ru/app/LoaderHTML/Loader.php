<?php

namespace App\LoaderHTML;

use App\LoaderHTML\LoaderConfig;
use App\Models\Process\ProcessModel;
use App\Services\Loader\LoaderClient;
use App\Services\Loader\LoaderService;

class Loader
{
   
    public function __construct(
        protected LoaderClient $loaderClient, 
        protected LoaderConfig $config = new LoaderConfig()){
        $this->initLoaderClient();
    }

    public function execute(){
        $processModel = new ProcessModel();
        while(true){
            $data = $processModel->getCollectionUrlsNewProcess($this->config->limitUrlsInGroup);
           
            if(!empty($data)){
                try {
                    $urls = array_column($data, "url");
                    $processModel->setStatusDownloading($urls);
                    echo 6;die;
                    $loaderService = $this->getLoaderService($data);
                    echo 5;
                    $urls = array_keys($loaderService->loadAll());
                    $processModel->setStatusWaitParsingAndFlagDownloaded($urls);
                  
                }catch(\Throwable $e){
                    throw $e;
                }
            }
        }
    }

    protected function initLoaderClient():void{
        $this->loaderClient->requestTimeount = $this->config->requestTimeout;
        $this->loaderClient->addRequestHeaders($this->config->requestHeaders);
    }

    protected function getLoaderService(string|array $urls):LoaderService{
        return new LoaderService($this->loaderClient, $urls, [
            "pathDirStorageHTML" => $this->config->pathDirStorageHTML,
          //  "parseAll" => true
        ]);
    }
}
