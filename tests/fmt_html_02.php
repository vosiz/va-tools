<?php

use \VaTools\Format\HtmlBuilder as Html;
use \VaTools\Format\Html\Elements\HtmlListItem;

function TestFmtHtml_Factory() {

    $html = new Html();

    // block elements
    $html->AddToBody(Html::Div('wrapper content'));
    $html->AddToBody(Html::P('paragraph'));

    // headings
    for($i = 1; $i <= 6; $i++)
        $html->AddToBody(Html::H($i, 'Heading '.$i));

    // inline / interactive
    $html->AddToBody(Html::A('click me', '/path'));
    $html->AddToBody(Html::Button('Submit', 'submit'));
    $html->AddToBody(Html::Input('username', 'john'));

    // unordered list — items from array
    $html->AddToBody(Html::Ul(['Item A', 'Item B']));

    // ordered list — AddItem + SetFirst/SetLast
    $ol = Html::Ol(['Second', 'Third']);
    $ol->SetFirst(new HtmlListItem('First'));
    $ol->AddItem('Fourth');
    $html->AddToBody($ol);

    return $html->Render();
}
