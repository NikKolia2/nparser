<?php

use App\Models\Process\ProcessModel;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$page = 1;
$limit = 5000;
$process = new ProcessModel();
$processes = $process->query()->select()->from($process::getTableName())->where("status_id", 4)->paginationLimit($page, $limit)->fetchArrays();
$files = [];
foreach($processes as $p){
    $url = $p["url"];
    $filename = hash("sha256", $url);
    $filepath = dirname(__DIR__, 3)."/storage/html/$filename.html";
    $files[] = $filepath;
}

$zip = new ZipArchive();

# create a temp file & open it
$create = $zip->open(__DIR__ . '/archive.zip', ZipArchive::CREATE);

if ( $create === TRUE) {
    echo "\n Арихв создан\n";
} else {
    echo 'Архив не создан, код ошибки: ', $create;
    fwrite(STDERR, "Error while creating archive file");
    exit(1);
}

# loop through each file
foreach ($files as $file) {
    # download file
    if(file_exists($file)){
        $download_file = file_get_contents($file);

   
        #add it to the zip
        $zip->addFromString(basename($file), $download_file);
    }
}

# close zip
$zip->close();

