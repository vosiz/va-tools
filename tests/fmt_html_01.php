<?php

use \VaTools\Format\HtmlBuilder as Html;

// full document render — DOCTYPE + html/head/body skeleton
function TestFmtHtml_Document() {

    $html = new Html();
    return $html->Render();
}

// add element to head
// expected: <html><head><meta charset="utf-8"></head><body></body></html>
function TestFmtHtml_AddToHead() {

    $html = new Html();
    $meta = Html::CreateElement('meta');
    $meta->AddAtts(['charset' => 'utf-8']);
    $html->AddToHead($meta);
    return $html->Render();
}

// add element to body
// expected: <html><head></head><body><p>Hello</p></body></html>
function TestFmtHtml_AddToBody() {

    $html = new Html();
    $html->AddToBody(Html::CreateElement('p', 'Hello'));
    return $html->Render();
}
