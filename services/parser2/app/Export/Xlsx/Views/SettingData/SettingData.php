<?php

namespace App\Export\Xlsx\Views\SettingData;

class SettingData
{
    public Header $header;
    public Body $body;
    public function __construct(
        Header|array $header,
        Body|array $body
    ){
        if(is_array($header)){
            $this->header = new Header(...$header);
        }else{
            $this->header = $header;
        }
        
        if(is_array($body)){
            $this->body = new Body(...$body);
        }else{
            $this->body = $body;
        }
    }
}
