<?php

use App\Services\HelperService;

require('config/bootstrap.php');

$path = dirname(__DIR__, 3)."/storage/html/";
HelperService::clearAllHTMLFiles($path);
