<?php

use \VaTools\Format\XmlBuilder as Xml;

function TestFmtXml_Root() {

    $xml = new Xml('myroot');
    return $xml;
}

function TestFmtXml_Struct() {

    $xml = TestFmtXml_Root();

    $new_root = Xml::CreateRoot("new");
    $lvl1 = Xml::CreateElement("level1");
    $lvl2 = Xml::CreateElement("level2");
    $lvl3 = Xml::CreateElement("level3");
    
    $lvl3->SetParent($lvl2);
    $lvl2->SetParent($lvl1);
    $lvl1->SetParent($new_root);
    $xml->SetRoot($new_root);

    return $xml;
}

function TestFmtXml_Atts() {

    $xml = TestFmtXml_Struct();
    $root = $xml->GetRoot();
    $root->AddAtts(['assoc1' => 123, 'assoc2' => 'str']);
    $root->AddAtts('readonly');

    return $xml;
}
