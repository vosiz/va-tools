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

function TestUrlParser_NoKeys() {

    $struct = UrlStruct::Create('localhost');
    $parser = new Parser($struct);

    return $parser;
}

function TestUrlParser_GetNonExisting() {

    $struct = UrlStruct::Create('localhost', ['controller', 'action']);
    $parser = new Parser($struct);

    return $parser->GetPartByKey('nonexisting');

    return $parser;
}

function TestUrlParser_TooShort() {

    $struct = UrlStruct::Create('localhost', ['controller', 'action', 'a', 'b', 'c']);
    $parser = new Parser($struct);

    return $parser->GetPartByKey('c');

    return $parser;
}