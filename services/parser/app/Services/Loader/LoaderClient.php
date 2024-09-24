<?php

namespace App\Services\Loader;

use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\Remote\RemoteWebDriver;

class LoaderClient
{
    protected $requestHeaders = [];
    protected $cookies = [];

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

    public function addCookie(string $name, $value){
        $this->cookies[$name] = $value;
    }

    public function initCookies(){
        foreach($this->cookies as $key => $value){
            $this->client->manage()->addCookie(new Cookie($key, $value));
        }
        
    }
}
