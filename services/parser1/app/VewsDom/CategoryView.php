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

    public function getAttributeProducts(){
        return [];
    }
}
