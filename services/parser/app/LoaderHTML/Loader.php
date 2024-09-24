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
            $urls = $processModel->getCollectionUrlsNewProcess($this->config->limitUrlsInGroup);
           
            if(!empty($urls)){
                try {
                    $processModel->setStatusDownloading($urls);
                    $loaderService = $this->getLoaderService($urls);
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
        ]);
    }
}
