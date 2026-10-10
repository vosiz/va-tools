<?php

use Vosiz\VaTools\Parser\UrlStructure as UrlStruct;
use Vosiz\VaTools\Parser\UrlParser as Parser;


function Fix1_Urlparser_Localhost() {

    $struct = UrlStruct::Create('localhost/php-projects', ['module', 'controller', 'action']);
    $parser = new Parser($struct);

    return $parser;
}

function Fix1_Urlparser_LocalAddress() {

    $struct = UrlStruct::Create('192.168.0.199', ['module', 'controller', 'action']);
    $parser = new Parser($struct);

    return $parser;
}

function Fix1_Urlparser_Empty() {

    $struct = UrlStruct::Create('', ['module', 'controller', 'action']);
    $parser = new Parser($struct);

    return $parser;
}