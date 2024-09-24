<?php

namespace App\ParserHTML;

use App\Models\Process\ProcessModel;

class Parser
{
   
    public function __construct(
        protected ParserConfig $config = new ParserConfig()){

    }

    public function execute(){
      
        while(true){
            $processes = $this->getActiveProcesses();
            if(empty($processes)) continue;

            $urls = array_column($processes, "url");
            ProcessModel::setStatusParsing($urls);
            
            foreach($processes as $process){
                $pathToFile = $this->config->getPathFileByUrl($process["url"]);
                $namespace = $process->namespace;
             
                $viewParser = new $namespace($pathToFile);
                if($viewParser->execute()){
                    ProcessModel::setStatusSuccess($process["url"]);
                }
            }
        }
    }

    protected function getActiveProcesses():array{
        return ProcessModel::parserProcesses()->limit($this->config->limitProcessInGroup)->get()->toArray();
    }
}
