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

    public function getAttributeArticle(){
        $xpath = new DOMXPath($this->dom);
        $articleNode = $xpath->query("//*[@data-qa='product-code']//span");
        if($articleNode->length)
            return $articleNode->item(0)->textContent;
        
        return null;
    }

    public function getAttributeBreadcrumbs(){
        $xpath = new DOMXPath($this->dom);
        $nodeList = $xpath->query("//*[contains(@class, 'breadcrumbs')]//*[contains(@class, 'breadcrumbs-item')]");
    
        if($nodeList->length){
            $breadcrumbs = [];
            foreach($nodeList as $node){
                $aNode = $xpath->query($node->getNodePath().'//a');
                if($aNode){
                    $spanNode = $xpath->query($aNode->item(0)->getNodePath().'//span');
                    if($spanNode){
                        $category = $spanNode->item(0)->textContent;
                        $breadcrumbs[] = trim(strip_tags($category));
                    }
                }
            }

            return json_encode($breadcrumbs, JSON_UNESCAPED_UNICODE);
        }
            
        return null;
    }

    public function getAttributeMetaDescription(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//meta[@name='description']");
        if($node->length){
            return trim(strip_tags($node->item(0)->getAttribute('content')));
        }

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
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[@id='description']//*[@itemprop='description']");
        if($node->length){
            return trim(strip_tags($node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeBrand(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[@itemprop='brand']//*[contains(@class, 'XzwDFu')]//*[@data-v-2a0bbf6b]");
        if($node->length){
            return trim(strip_tags($node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeCountry(){
        $xpath = new DOMXPath($this->dom);
        $node = $xpath->query("//*[@itemprop='brand']//*[contains(@class, 'px2ZEf')]//*[@data-v-2a0bbf6b]");
        if($node->length){
            return trim(strip_tags($node->item(0)->textContent));
        }

        return null;
    }

    public function getAttributeComplectation(){
        $xpath = new DOMXPath($this->dom);
        $nodeList = $xpath->query("//*[contains(@class, 'fXY1dM')]//ul/li");
        if($nodeList->length){
            $complectations = [];
            foreach($nodeList as $node){
                $complectations[] = trim(strip_tags($node->textContent));
            }
            return json_encode($complectations, JSON_UNESCAPED_UNICODE);
        }

        return null;
    }

    public function getAttributeInfoPack(){
        $xpath = new DOMXPath($this->dom);
        $nodeList = $xpath->query("//*[contains(@class, 'M-OTgI')]//p[@data-v-2a0bbf6b]");
        if($nodeList->length){
            $arr = [];
            foreach($nodeList as $node){
                $arr[] = trim(strip_tags($node->textContent));
            }
            return json_encode($arr, JSON_UNESCAPED_UNICODE);;
        }

        return null;
    }

    public function getAttributeCharacteristics(){
        $xpath = new DOMXPath($this->dom);
        $optionsNodeList = $xpath->query("//*[@id='characteristics']//*[@data-qa='specification-item']");
        if($optionsNodeList->length){
            $arr = [];
            foreach($optionsNodeList as $node){
                $optionName = null;
                $optionNameNodeList = $xpath->query($node->getNodePath()."//*[@data-qa='specification-item-name']");
                if($optionNameNodeList->length){
                    $optionName = trim(strip_tags($optionNameNodeList->item(0)->textContent));
                  
                }
                
                $optionValue = null;
                $optionValueNodeList = $xpath->query($node->getNodePath()."//*[@data-qa='specification-item-value']");
                if($optionValueNodeList->length){
                    $optionValue = trim(strip_tags($optionValueNodeList->item(0)->textContent));
                }

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

            return $arr;
        }

        return null;
    }

    public function getAttributeImages(){
        $xpath = new DOMXPath($this->dom);
        $nodeList = $xpath->query("//*[contains(@class, 'goeetN')]//*[@data-qa='carousel-image']//*[contains(@class, 'content')]//*[contains(@class, 'item')]");
        if($nodeList->length){
            $gallery = [];
            foreach ($nodeList as $node) {
                $aNode = $xpath->query($node->getNodePath()."/a");
               
                if(!$aNode->length) continue;
               
                $url = $aNode->item(0)->getAttribute('href');
                $gallery[] = $url;
            }

            return $gallery;
        }

        return null;
    }
}
