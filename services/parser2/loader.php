<?php

require('config/bootstrap.php');

use App\LoaderHTML\Loader;
use App\LoaderHTML\LoaderConfig;
use App\Services\Loader\LoaderClient;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

// Chrome
$serverUrl = 'http://nparser_selenium:4444/wd/hub';
$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());

$loader = new Loader(new LoaderClient($driver), new LoaderConfig(
    requestTimeout: 120,
    pathDirStorageHTML: realpath("../../storage/html/")."/",
    limitUrlsInGroup: 20
));

$loader->execute();