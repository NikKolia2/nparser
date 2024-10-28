<?php

use App\Models\Process\ProcessModel;

require(dirname(__DIR__, 1)."/config/bootstrap.php");

$page = 1;
$limit = 5000;
$process = new ProcessModel();
$total =  $process->query()->select("COUNT(*) as total")->from($process::getTableName())->where("status_id", 4)->fetchFirstArray()["total"];
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
$create = $zip->open(__DIR__ . '/archive'.date("Y-m-d_H:i:s").'.zip', ZipArchive::CREATE);

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
        #add it to the zip
        $zip->addFile($file, basename($file));
    }
}

# close zip
$zip->close();
echo  $zip->getStatusString();


