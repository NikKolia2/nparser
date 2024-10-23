<?php

namespace App\ParserHTML;

abstract class AbstractParserView
{

    public $process;
    public function __construct(
        string $pathToFile,
        $process
    ){
        $this->process = $process;
    }

    abstract public function execute():bool;
}
