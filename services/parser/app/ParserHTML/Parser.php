<?php

namespace App\ParserHTML;

use App\Models\Process\ProcessModel;

class Parser
{
   
    public function __construct(
        protected ParserConfig $config = new ParserConfig()){

    }

    public function execute(){
        $processModel = new ProcessModel();
     
        while(true){
            $processes = $this->getActiveProcesses();
            if(empty($processes)) continue;
            $urls = array_map(fn($obj) => $obj->url, $processes);
            $processModel->setStatusParsing($urls);
            echo 2;
            foreach($processes as $process){
                echo 1;
                $pathToFile = $this->config->getPathFileByUrl($process->url);
                $namespace = $process->namespace;
             
                $viewParser = new $namespace($pathToFile);
                if($viewParser->execute()){
                    $processModel->setStatusSuccess($process->url);
                }
                echo 3;
            }
        }
    }

    protected function getActiveProcesses():array{
        $processModel = new ProcessModel();
        return $processModel->getCollectionUrlsParserProcess($this->config->limitProcessInGroup);
    }
}
