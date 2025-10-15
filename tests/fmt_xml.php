<?php

require_once(__DIR__.'/../src/format/xml/xml.php');

// TEST
// - 00 - simple
require_once(__DIR__.'/fmt_xml_00.php');
function TestBasics() {

    //$xml = TestFmtXml_Root();
    //debug($xml);

    //$xml = TestFmtXml_Struct();
    //debug($xml);

    $xml = TestFmtXml_Atts();
    //debug($xml);
    //var_dump($xml);
}

// - 01 - render
require_once(__DIR__.'/fmt_xml_01.php');
function TestRender() {

    echo TestFmtXml_Render();
}

