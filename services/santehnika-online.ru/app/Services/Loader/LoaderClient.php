<?php

namespace App\Services\Loader;

use Monolog\Logger;
use App\Services\PLogger;
use Facebook\WebDriver\Cookie;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Exception\TimeoutException;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\NoSuchElementException;

class LoaderClient
{
    protected $requestHeaders = [];
    protected $cookies = [];

    public ?int $requestTimeount = null;

    public function __construct(
        protected RemoteWebDriver $client,
        protected $dirSaveCaptcha
    ){
       
    }

    public function getClient():RemoteWebDriver{
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
    public function get(string $url){
        $this->client->get($url);

        $this->client->wait()->until(WebDriverExpectedCondition::invisibilityOfElementLocated(WebDriverBy::id('id_spinner')));
    
        try {
            $this->client->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[contains(@class, 'U8AUVKXLgoAaETSMMbwf')]/*[contains(@class, 'b-modal__main')]/button[@type='button']")));
            $modal = $this->client->findElement(WebDriverBy::xpath("//*[contains(@class, 'U8AUVKXLgoAaETSMMbwf')]/*[contains(@class, 'b-modal__main')]/button[@type='button']"));
            $modal->click();
        }catch(NoSuchElementException|TimeoutException $e){
            echo $e->getMessage();
            $this->client->takeScreenshot($this->dirSaveCaptcha."screen1.png");
        }
    }

    function setTimeout(){
        if($this->requestHeaders != null)
            $this->client->manage()->timeouts()->implicitlyWait($this->requestTimeount);
    }

    function getHTML(array $loaderData):string{
        echo 2;
        $this->get($loaderData['url']);
        $this->initCookies();
        $this->setTimeout();

        if($loaderData['type_id'] == 1){
            echo 1;
            try {
                $el = $this->client->findElement(WebDriverBy::xpath("//*[contains(@class, 'js-card-tabs-anchor')]/div[2]"));
                $this->client->wait()->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::xpath("//*[contains(@class, 'js-card-tabs-anchor')]/div[2]")));
                $this->client->executeScript("
                    let aboutProduct = document.querySelector('.b-preloader-ajax')?.cloneNode(true);
                    aboutProduct.id = 'nparser-about-product';
                    document.body.appendChild(aboutProduct);
                ");

                $el->click();
                $this->client->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[contains(@class, 'card-product-section-main')]//*[contains(@class, 'eGhYU27ERpoFI9K0pm4e')]")));
            }catch(NoSuchElementException|TimeoutException $e){
                if($e instanceof TimeoutException){
                    PLogger::log(Logger::ERROR, "Ошибка по таймауту. Страница {$loaderData['url']}");
                    $this->client->takeScreenshot($this->dirSaveCaptcha."screen.png");
                }
            }
        }else if($loaderData['type_id'] == 2){
            try {
                $this->client->wait()->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//*[contains(@class, 'yQrSKhJzdVM63N2kxXIH')]")));
            }catch(NoSuchElementException|TimeoutException $e){
                
            }
            
        }
        
        return $this->client->executeScript("return document.getElementsByTagName('html')[0].innerHTML");
    }
}
