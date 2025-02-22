<?php

use \VaTools\Structure\Flagword as Fword;

function TestFlags_Flag() {

    $fword = new Fword(10);

    $flag = $fword->ByIndex(6);
    $flag->Set();
    
    return $flag->IsSet();
}

function TestFlags_Flag2() { 

    $fword = new Fword(8);

    $flag = $fword->ByIndex(3);
    $flag->Set(0);
    $flag->Toggle();

    return $flag;
}

function TestFlags_Flag3() { 

    $fword = new Fword(8);

    $flag = $fword->ByIndex(7);
    $flag->Set();
    $flag->Unset();

    return $flag;
}