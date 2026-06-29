<?php

use \VaTools\Format\HtmlBuilder as Html;

// void element — no self-closing, no closing tag
// expected: <br>
function TestFmtHtml_VoidElement() {

    $el = Html::CreateElement('br');
    return $el->Render();
}

// non-void empty element — must close even without content
// expected: <div></div>
function TestFmtHtml_NonVoidEmpty() {

    $el = Html::CreateElement('div');
    return $el->Render();
}

// non-void with text
// expected: <p>hello</p>
function TestFmtHtml_NonVoidText() {

    $el = Html::CreateElement('p', 'hello');
    return $el->Render();
}

// attributes — assoc + boolean void
// expected: <input type="text" name="email" required>
function TestFmtHtml_Atts() {

    $el = Html::CreateElement('input');
    $el->AddAtts(['type' => 'text', 'name' => 'email']);
    $el->AddAtts('required');
    return $el->Render();
}
