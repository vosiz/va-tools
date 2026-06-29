<?php

use \VaTools\Format\HtmlBuilder as Html;
use \VaTools\Format\Html\HtmlException;
use \VaTools\Format\Html\TableInvalidDimensions;

// table with headers and data
// expected: <table><thead><tr><th>Name</th><th>Age</th></tr></thead><tbody><tr><td>Alice</td><td>30</td></tr><tr><td>Bob</td><td>25</td></tr></tbody></table>
function TestFmtHtml_Table() {

    $table = Html::Table(
        [['Alice', 30], ['Bob', 25]],
        ['Name', 'Age']
    );
    return $table->Render();
}

// table without headers
// expected: <table><tbody><tr>...</tr></tbody></table>
function TestFmtHtml_TableNoHeaders() {

    $table = Html::Table([['X', 'Y'], ['A', 'B']]);
    return $table->Render();
}

// null/empty cell values — rendered as empty string
function TestFmtHtml_TableNullCells() {

    $table = Html::Table([[null, false, '', 'ok']]);
    return $table->Render();
}

// AddRow via factory Tr + SetHeaders after construction
function TestFmtHtml_TableAddRow() {

    $table = Html::Table([['one', 'two']]);
    $table->SetHeaders(['Col1', 'Col2']);
    $table->AddRow(Html::Tr(['three', 'four']));
    return $table->Render();
}

// dimension mismatch — should throw TableInvalidDimensions with inner InvalidArgumentException
function TestFmtHtml_TableException() {

    try {

        Html::Table([['a', 'b'], ['x']]);
        return 'FAIL: no exception thrown';

    } catch(TableInvalidDimensions $e) {

        $inner = $e->getPrevious() ? get_class($e->getPrevious()) : 'none';
        return 'OK: '.$e->getMessage().' | inner: '.$inner.' | element: '.$e->GetElement();
    }
}

// invalid heading level — should throw HtmlException with inner InvalidArgumentException
function TestFmtHtml_HeadingException() {

    try {

        Html::H(0, 'bad');
        return 'FAIL: no exception thrown';

    } catch(HtmlException $e) {

        $inner = $e->getPrevious() ? get_class($e->getPrevious()) : 'none';
        return 'OK: '.$e->getMessage().' | inner: '.$inner.' | element: '.$e->GetElement();
    }
}
