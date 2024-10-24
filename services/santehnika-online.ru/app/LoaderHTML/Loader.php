<?php

namespace App\LoaderHTML;

use App\LoaderHTML\LoaderConfig;
use App\Models\Process\ProcessModel;
use App\Services\Loader\LoaderClient;
use App\Services\Loader\LoaderService;
use App\Services\PLogger;
use Monolog\Logger;

class Loader
{
   
    public function __construct(
        protected LoaderClient $loaderClient, 
        protected LoaderConfig $config = new LoaderConfig()){
        $this->initLoaderClient();
    }

    public function execute(){
        $processModel = new ProcessModel();
        $processName = uniqid("", true).".txt";
        file_put_contents($this->config->pathToProcessesDir.$processName, 1);
        while(file_exists($this->config->pathToProcessesDir.$processName)){
            PLogger::log(Logger::INFO, "получаем группу ссылок");
            $data = $processModel->getCollectionUrlsNewProcess($this->config->limitUrlsInGroup);
           
            if(!empty($data)){
                try {
                    PLogger::log(Logger::INFO, "Ссылки получены");
                    $urls = array_column($data, "url");
                    $processModel->setStatusDownloading($urls);

                    $loaderService = $this->getLoaderService($data);
                    $urls = array_keys($loaderService->loadAll());
                    $processModel->setStatusWaitParsingAndFlagDownloaded($urls);
                }catch(\Throwable $e){
                    PLogger::log(Logger::ERROR, $e->getMessage());
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
