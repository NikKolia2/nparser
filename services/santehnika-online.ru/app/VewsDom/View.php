<?php
namespace App\VewsDom;

use DOMDocument;

class View
{
    protected $dom;
    public function __construct(string $html, $isPath = false) {
        $this->dom = new DOMDocument();
        if(!$isPath){
            @$this->dom->loadHTML($html);
        }else{
            @$this->dom->loadHTMLFile($html);
        }
    }

    public function __get($name){
        $method = "getAttribute".strtoupper($name);
        if(method_exists($this, $method)){
            return $this->{$method}();
        }

        return null;
    }
}
