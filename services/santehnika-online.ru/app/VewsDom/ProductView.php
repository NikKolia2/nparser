<?php
namespace App\VewsDom;

use App\Services\HelperService;
use DOMXPath;

class ProductView extends View
{
    public function getAttributeMetaTitle(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("/html/head/title");
        if($node->length){
            return trim(strip_tags($node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeH1(){
        $xpath = new DOMXPath($this->dom);
        $h1Node = $xpath->query("//h1");
        if($h1Node->length){
            return trim(strip_tags($h1Node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeCode(){
        $xpath = new DOMXPath($this->dom);
        $articleNode = $xpath->query("//*[contains(@class, 'card-product')]//*[contains(@class, 'LIQSG6jkG8K8J84B7Gi5')]");
        if($articleNode->length)
            return trim(strip_tags(str_replace('Код:', '', $articleNode->item(0)->textContent)));
        
        return null;
    }

    public function getAttributePrice(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[contains(@class, 'card-product')]//*[contains(@class, 'main')]//*[contains(@class, 'b-price__price--main')]//*[contains(@class, 'b-price__price-core')]");
        if($node->length)
            return trim(str_replace("₽", "", trim(strip_tags($node->item(0)->textContent))));
        
        return null;
    }

    public function getAttributeOldPrice(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[contains(@class, 'card-product')]//*[contains(@class, 'main')]//*[contains(@class, 'b-price__price--old')]//*[contains(@class, 'b-price__price-core')]");
        if($node->length)
            return trim(str_replace("₽", "", trim(strip_tags($node->item(0)->textContent))));
        
        return null;
    }

    public function getAttributeArticle(){
        $xpath = new DOMXPath($this->dom);
        $articleNode = $xpath->query("//*[@id='nparser-about-product']//*[contains(@class, 'LksPR0PO0h4lVCCJKDEN')]//*[contains(@class, 'SC5w5GYRFIsb9eJmdWIe') and text()='Артикул']/parent::*/parent::*/*[contains(@class, 'xHtZUMuYkoN39SOq1kP0')]");
        if($articleNode->length){
            return $articleNode->item(0)->textContent;
        }
        return null;
    }

    public function getAttributeBreadcrumbs(){
       
            
        return null;
    }

    public function getAttributeMetaDescription(){
       
        return null;
    }

    public function getAttributeKeywords(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//meta[@name='keywords']");
        if($node->length){
            return trim(strip_tags($node->item(0)->getAttribute('content')));
        }

        return null;
    }

    public function getAttributeDescription(){

        return null;
    }

    public function getAttributeBrand(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[@id='nparser-about-product']//*[contains(@class, 'LksPR0PO0h4lVCCJKDEN')]//*[contains(@class, 'SC5w5GYRFIsb9eJmdWIe') and text()='Бренд']/parent::*/parent::*/*[contains(@class, 'xHtZUMuYkoN39SOq1kP0')]/a");
        if($node->length){
            return trim(strip_tags($node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeCountry(){

        return null;
    }

    public function getAttributeComplectation(){

        return null;
    }

    public function getAttributeInfoPack(){

        return null;
    }

    public function getAttributeCharacteristics(){
        $xpath = new DOMXPath($this->dom);
        $optionsNodeList = $xpath->query("//*[contains(@class, 'eGhYU27ERpoFI9K0pm4e')]//*[contains(@class, 'sTuRREjxkcM2oZZvFsNv')]");
        if($optionsNodeList->length){
            foreach($optionsNodeList as $node){
                $nodeHeader = $xpath->query($node->getNodePath()."//*[contains(@class, 'zZM8feNJ9uE4brpqPGxx')]");
                $nodeBody = $xpath->query($node->getNodePath()."//*[contains(@class, 'p77yJr3gHcMqPC0fAxK2')]/span");
                if($nodeHeader->length && $nodeBody->length){
                    $optionName = trim(strip_tags($nodeHeader->item(0)->textContent));
                    $optionValue = trim(strip_tags($nodeBody->item(0)->textContent));

                    $optionKey = HelperService::translite($optionName);
                    $optionKey = str_replace([':',"(",")","\\","/"], '_', $optionKey);
                    $optionKey = rtrim(str_replace([" ", "'"], '', $optionKey), "_");
                    $optionKey = strtolower($optionKey);

                    $arr[] = [
                        "option_name" => $optionName,
                        "option_value" => $optionValue,
                        "option_key" => $optionKey
                    ];
                }
            }

            return $arr;
        }

        return null;
    }

    public function getAttributeImages(){
        $xpath = new DOMXPath($this->dom);
        $nodeList = $xpath->query("//*[contains(@class, 'card-product')]//*[contains(@class, 'MAsfHIY3eg66wlBwCZKZ')]//*[contains(@class, 'swiper-slide')]//img");
        if($nodeList->length){
            $gallery = [];
            foreach ($nodeList as $node) {               
                $url = $node->getAttribute('src');
                if(empty($url)){
                    $url = $node->getAttribute('data-src');
                }
                $gallery[] = $url;
            }

            return $gallery;
        }

        return null;
    }

    public function getAttributeWarehouse(){

        return null;
    }
}
