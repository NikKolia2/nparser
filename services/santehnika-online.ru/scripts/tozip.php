<?php

use App\Models\Process\ProcessModel;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$process = new ProcessModel();
$processes = $process->query()->select()->from($process::getTableName())->where("status_id", 4)->fetchArrays();
$files = [];
foreach($processes as $p){
    $url = $p["url"];
    $filename = hash("sha256", $url);
    $filepath = dirname(__DIR__, 3)."/storage/html/$filename.html";
    $files[] = $filepath;
}

$zip = new ZipArchive();

# create a temp file & open it
$zip->open(__DIR__ . '/archive.zip', ZipArchive::CREATE);

# loop through each file
foreach ($files as $file) {
    # download file
    $download_file = file_get_contents($file);

    #add it to the zip
    $zip->addFromString(basename($file), $download_file);
}

# close zip
$zip->close();

