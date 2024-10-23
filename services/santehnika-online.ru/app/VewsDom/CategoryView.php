<?php
namespace App\VewsDom;

use DOMXPath;



class CategoryView extends View
{
    public function getAttributeH1(){
        $xpath = new DOMXPath($this->dom);
        $h1Node = $xpath->query("//h1");
        if($h1Node){
            return $h1Node->item(0)->textContent;
        }

        return null;
    }

    public function getAttributeProductsUrls(){
        $xpath = new DOMXPath($this->dom);
        $nodesList = $xpath->query("//*[contains(@class, 'b-catalog-products__react')]//*[contains(@class, 'EbhTNkqeUTsyhuWx_WG9')]/a");
        if($nodesList->length){
            $urls = [];
            for($i = 0; $i < $nodesList->length; $i++){
                $urls[] = "https://santehnika-online.ru".$nodesList->item($i)->getAttribute('href');
            }

            return $urls;
        }

        return [];
    }

    public function getAttributePaginationUrls(){
        $xpath = new DOMXPath($this->dom);
        $nodesList = $xpath->query("//*[contains(@class, 'qEXKVc9lQmrQSlkSyYWE')]/a");
        if($nodesList->length){
            $urls = [];
            $domainSite = "https://santehnika-online.ru";
            $categoryPath = $nodesList->item(0)->getAttribute("href");
            $endPage = (int)$nodesList->item($nodesList->length - 2)->textContent;
        
            for($i = 2; $i <= $endPage; $i++){
                $urls[] = $domainSite.$categoryPath."?PAGEN_1=".$i;
            }

            return $urls;
        }

        return [];
    }

    public function getAttributeCategoriesUrls(): array {
        $xpath = new DOMXPath($this->dom);
        $categoriesNodes = $xpath->query("//*[contains(@class, 'yQrSKhJzdVM63N2kxXIH')]//a");
    
        if($categoriesNodes->length){
            $urls = [];

            for($i = 0; $i < $categoriesNodes->length; $i++){
                $urls[] = "https://santehnika-online.ru".$categoriesNodes->item($i)->getAttribute('href');
            }

            return $urls;
        }

        return [];
    }
}
