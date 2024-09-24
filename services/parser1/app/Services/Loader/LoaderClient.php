<?php

namespace App\Services\Loader;

use JonnyW\PhantomJs\Client;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class LoaderClient
{
    protected $requestHeaders = [];
    public ?int $requestTimeount = null;

    public function __construct(
        protected RemoteWebDriver $client
    ){

    }

    public function getClient(){
        return $this->client;
    }

    public function addRequestHeader($key, $value){
        $this->requestHeaders[] = [$key, $value];
    }

    public function addRequestHeaders(array $headers){
        foreach($headers as $key => $value){
            $this->addRequestHeader($key, $value);
        }
    }

    public function getRequestHeaders(){
        return $this->requestHeaders;
    }
}
