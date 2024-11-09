<?php
require(dirname(__DIR__).'/config/bootstrap.php');

use App\Models\Process\ProcessNameModel;

$processNameModel = new ProcessNameModel();
$limit = 10;
$total = $processNameModel->getCountProcessesNoSuccess()/10;

for($page = 1; $page <= $total; $page++){
    $processNameModel->updateProcessStatusOnSuccess($page, $limit);
}