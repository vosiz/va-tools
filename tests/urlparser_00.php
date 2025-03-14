<?php

use Vosiz\VaTools\Parser\UrlStructure as UrlStruct;
use Vosiz\VaTools\Parser\UrlParser as Parser;

function TestUrlParser_Structure() {

    $urlstr = UrlStruct::Create('localhost', ['controller', 'action']);
    return $urlstr;
}

function TestUrlParser_Parsing() {

    $struct = UrlStruct::Create('localhost', ['module', 'controller', 'action']);
    $parser = new Parser($struct);

    return $parser;
}

function TestUrlParser_GetPars() {

    $struct = UrlStruct::Create('localhost', ['module', 'controller', 'action']);
    $parser = new Parser($struct);

    return $parser->GetPartByKey('action');
}