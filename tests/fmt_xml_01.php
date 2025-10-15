<?php

use \VaTools\Format\XmlBuilder as Xml;

function TestFmtXml_Render() {

    $xml = TestFmtXml_Atts();
    $str = $xml->Render(); 
    var_dump($str);
    return $str;
}
