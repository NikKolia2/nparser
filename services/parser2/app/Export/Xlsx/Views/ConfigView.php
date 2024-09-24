<?php

namespace App\Export\Xlsx\Views;


use ReflectionClass;
use App\Export\Xlsx\BuilderData\EnumBuildersData;
use App\Export\Xlsx\BuilderData\AbstractBuilderData;

class ConfigView
{
    public AbstractBuilderData $builderData;
   

    public function __construct(
        public readonly string $name,
        EnumBuildersData|string $builderData,
        public readonly int $limitQuery = 20,
        public readonly string $actionsPath = ""
    ){
        if(is_string($builderData)){
            $this->builderData = new (EnumBuildersData::from($builderData)->getClass())();
        }else{
            $this->builderData = new ($builderData->getClass())();
        }
    }

    public static function parse(array $data):static{
        $reflectionClass = new ReflectionClass(static::class);
        $parameters = $reflectionClass->getConstructor()->getParameters();
        if(!empty($parameters)){
            $newParams = [];
            foreach($parameters as $param){
                if(isset($data[$param->name])){
                    $newParams[$param->name] = $data[$param->name];
                }
            }

            return new static(...$newParams);
        }else{
            return new static();
        }
    }

    public function getBuilderData(){

    }
}
