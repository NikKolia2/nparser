<?php

namespace App\ParserHTML;

abstract class AbstractParserView
{
    public function __construct(
        string $pathToFile
    ){}

    abstract public function execute():bool;
}
