<?php

use App\System\Export\ConfigExporter;
use App\System\Export\Xlsx\ConfigXlsxExport;
use App\System\Export\Xlsx\Views\Actions\AbstractAction;
use App\Export\Xlsx\Actions\ActionGetTitlesCharacteristics;

ini_set('memory_limit', '10000M');
require('config/bootstrap.php');

$config = require('config/export.php');

$exporter = new $config["xlsx"]["exporter"]($config["xlsx"]["data"], new ConfigXlsxExport(...$config["xlsx"]["config"]));
$exporter->execute();


// $str = "getVar(\"{pb()}\", pr(pr(pr(pr('1', 2, 3))), pt()), pr(pr(pr())))";

// function call_func($str, $fn){
//     if(preg_match("/^\".+\"$|^'.+'$/", $str, $m)){
//         $data = str_split($str);
//         unset($data[0]);
//         unset($data[count($data)]);
//         return implode("", $data);
//     }

//     if(preg_match_all("/[^\(]\w+\([^\)]*\)/", $str, $matches1)){
//         foreach($matches1[0] as $match){
//             preg_match_all("/\w+\([^\(]*\)/", $match, $m);
//             $result = $fn($m[0][0]);
//             $str = str_replace($m[0][0], $result, $str);
//         }

    
//         return call_func($str, $fn);
//     }

//     return $str;
// }




// echo call_func($str, function($fn){
//     echo $fn.PHP_EOL;
//     return "str";
    
// });