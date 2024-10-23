<?php

namespace App\System\Export\Xlsx\Views\Actions;

use App\Services\HelperService;

class ActionsCollection
{
    private array $collection = [];
    public function __construct(array $collection = [], public ?string $view = null){
        if(!empty($collection)){
            foreach($collection as $item){
                $this->add($item);
            }
        }
    }

    public function add(string $action){
        if($this->instanceof($action)){
            $this->collection[$action::$name] = $action;
        }
    }

    public function instanceof(string $action):bool{
        if(HelperService::instanceOfByNamespace($action, AbstractAction::class)){
            if($this->view){
                $views = HelperService::getParentsByNamespace($this->view);
                $views[] = $this->view;

                foreach($views as $view){
                    if(HelperService::instanceOfByNamespace($action::getView(), $view)){
                        return true;
                    }
                }

                return false;
            } 

            return true;
        }
        
        return false;
    }

    public function toArray():array{
        return $this->collection;
    }

    public function merge(ActionsCollection $collection){
        $this->collection = array_merge($this->collection, $collection->toArray());
    }
}
