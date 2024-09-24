<?php
require('config/bootstrap.php');

use App\ParserHTML\Parser;
use App\ParserHTML\ParserConfig;

$parser = new Parser(new ParserConfig(
    storageHTML: dirname(__DIR__, 2)."/storage/html/"
));

$parser->execute();