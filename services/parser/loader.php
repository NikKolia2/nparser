<?php

require('config/bootstrap.php');

use App\LoaderHTML\Loader;
use Facebook\WebDriver\Cookie;
use App\LoaderHTML\LoaderConfig;
use App\Services\Loader\LoaderClient;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

// Chrome
$serverUrl = 'http://nparser_selenium:4444/wd/hub';
$driver = RemoteWebDriver::create($serverUrl, DesiredCapabilities::chrome());
$loaderClient = new LoaderClient($driver);
$loaderClient->addCookie("vi_represent_id", '-1');

$loader = new Loader($loaderClient, new LoaderConfig(
    requestTimeout: 120,
    pathDirStorageHTML: realpath("../../storage/html/")."/",
    limitUrlsInGroup: 20
));

$loader->execute();