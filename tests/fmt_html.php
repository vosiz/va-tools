<?php

require_once(__DIR__.'/../src/format/xml/xml.php');
require_once(__DIR__.'/../src/format/html/html.php');

// TEST
// - 00 - element behavior (void, non-void, attributes)
require_once(__DIR__.'/fmt_html_00.php');
function TestElements() {

    echo TestFmtHtml_VoidElement();
    echo TestFmtHtml_NonVoidEmpty();
    echo TestFmtHtml_NonVoidText();
    echo TestFmtHtml_Atts();
}

// - 01 - document structure (head, body, render)
require_once(__DIR__.'/fmt_html_01.php');
function TestDocument() {

    echo TestFmtHtml_Document();
    echo TestFmtHtml_AddToHead();
    echo TestFmtHtml_AddToBody();
}

// - 02 - factory methods
require_once(__DIR__.'/fmt_html_02.php');
function TestFactory() {

    echo TestFmtHtml_Factory();
}

// - 03 - table
require_once(__DIR__.'/fmt_html_03.php');
function TestTable() {

    echo TestFmtHtml_Table();
    echo TestFmtHtml_TableNoHeaders();
    echo TestFmtHtml_TableNullCells();
    echo TestFmtHtml_TableAddRow();
    echo TestFmtHtml_TableException();
    echo TestFmtHtml_HeadingException();
}
