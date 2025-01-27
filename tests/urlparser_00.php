<?php

use VaTools\Url\UrlStructure as UrlStruct;
use VaTools\Url\UrlParser as Parser;

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