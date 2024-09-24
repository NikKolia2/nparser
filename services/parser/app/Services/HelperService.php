<?php

namespace App\Services;

use ReflectionClass;

class HelperService
{
    public static function translite(string $st):string{
        $st = strtr($st, array(
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'c',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch',
            'ь' => '\'', 'ы' => 'y', 'ъ' => '\'',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    
            'А' => 'A', 'Б' => 'B', 'В' => 'V',
            'Г' => 'G', 'Д' => 'D', 'Е' => 'E',
            'Ё' => 'E', 'Ж' => 'Zh', 'З' => 'Z',
            'И' => 'I', 'Й' => 'Y', 'К' => 'K',
            'Л' => 'L', 'М' => 'M', 'Н' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R',
            'С' => 'S', 'Т' => 'T', 'У' => 'U',
            'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
            'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sch',
            'Ь' => '\'', 'Ы' => 'Y', 'Ъ' => '\'',
            'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya',
        ));

        return $st;
    }


    public static function getClassFiles($directory, $callback)
    {
        $files = scandir($directory);

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }


            $path = $directory . '/' . $file;


            if (is_file($path) && pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                require_once $path;

                $className = basename($file, '.php');

                $namespace = "\\App\\" . str_replace("/", "\\", explode("src/", $directory)[1]);

                if (class_exists($namespace . "\\" . $className)) {

                    $callback($namespace . "\\" . $className);
                }
            } elseif (is_dir($path)) {

                static::getClassFiles($path, $callback);
            }
        }
    }

    public static function instanceOfByNamespace($namespaceFirst, $namespaceSecond):bool{
        $rf = new ReflectionClass($namespaceFirst);
        $parent = $rf->getParentClass();
    
        if($parent){
            if($parent->getName() === $namespaceSecond){
                return true;
            }else {
                return static::instanceOfByNamespace($parent->getName(), $namespaceSecond);
            }
        }
    
        return false;
    }

    public static function getParentsByNamespace($namespace):array{
        $rf = new ReflectionClass($namespace);
        $parents = [];
        while($parent = $rf->getParentClass()){
            $parents[] = $parent;
            $rf = new ReflectionClass($parent->getName());
        }
    
        return $parents;
    }
}
