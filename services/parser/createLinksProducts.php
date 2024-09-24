<?php

//https://www.vseinstrumenti.ru/search_main.php?what=13916440
$ids = file('links.txt', FILE_IGNORE_NEW_LINES);
$urls = "";
foreach($ids as $id){
    $urls .= "https://www.vseinstrumenti.ru/search_main.php?what=".$id."\n";
}

file_put_contents("links.txt", $urls);