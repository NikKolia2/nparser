<?php

require('config/bootstrap.php');

use App\LoaderHTML\Loader;
use App\LoaderHTML\LoaderConfig;
use App\Services\Loader\LoaderClient;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;

// Chrome
$serverUrl = 'http://nparser_selenium:4444/wd/hub';
$capabilities = DesiredCapabilities::chrome();
$custom_user_agent = 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0.0.0 YaBrowser/24.7.0.0 Safari/537.36';

$chromeOptions = new ChromeOptions();
// to run Chrome in headless mode
$chromeOptions->addArguments(['--disable-blink-features=AutomationControlled', "--user-agent=$custom_user_agent"]); // <- comment out for testing
$chromeOptions->setExperimentalOption("excludeSwitches", ["enable-automation"]);
$chromeOptions->setExperimentalOption('useAutomationExtension', false);
// register the Chrome options
$capabilities->setCapability(ChromeOptions::CAPABILITY_W3C, $chromeOptions);
$driver = RemoteWebDriver::create($serverUrl, $capabilities);
$driver->executeScript("Object.defineProperty(navigator, 'webdriver', {get: () => undefined})");

$loaderClient = new LoaderClient($driver, dirname(__DIR__, 2)."/storage/html/");

$loader = new Loader($loaderClient, new LoaderConfig(
    requestTimeout: 100,
    pathDirStorageHTML: dirname(__DIR__, 2)."/storage/html/",
    pathToProcessesDir:  dirname(__DIR__, 1)."/temp/processes/",
    limitUrlsInGroup: 20
));

$loader->execute();