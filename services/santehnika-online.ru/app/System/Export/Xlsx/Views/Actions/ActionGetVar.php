<?php

namespace App\System\Export\Xlsx\Views\Actions;

use App\Services\HelperService;
use App\System\Export\Xlsx\Views\Vars\AbstractVar;

class ActionGetVar extends AbstractAction
{
    public static $name = "getVar";
  
    public function execute($value):mixed{
        $varname = parent::execute($value);

        return $this->get($varname);
    }

    public function get(string $varname){
        $res = null;

        HelperService::getClassFiles(realpath(dirname(__DIR__, 5)."/Export/Xslx/Views/Vars"), function($cls) use ($varname, &$res) {
            if(HelperService::instanceOfByNamespace($cls, AbstractVar::class) && $cls::$name == $varname){
                $var = new $cls();
                $res = $var->execute();
            }
        });
        
        if($res === null){
            HelperService::getClassFiles(realpath(dirname(__DIR__, 1)."/Export/Xslx/Views/Vars"), function($cls) use ($varname, &$res) {
                if(HelperService::instanceOfByNamespace($cls, AbstractVar::class) && $cls::$name == $varname){
                    $var = new $cls();
                    $res = $var->execute();
                }
            });
        }

        return $res;
    }

}
