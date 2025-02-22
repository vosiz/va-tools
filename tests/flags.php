<?php

require_once(__DIR__.'/../src/struct/flag.php');

// TEST
// - 00
require_once(__DIR__.'/flags_00.php');
function TestBasics() {

    $size = TestFlags_Size();
    var_dump($size);

    $flags = TestFlags_Set();
    var_dump($flags);
}

// - 01
require_once(__DIR__.'/flags_01.php');
function TestBasics2() {

    $flag = TestFlags_Flag();
    var_dump($flag);

    $flag = TestFlags_Flag2();
    var_dump($flag);

    $flag = TestFlags_Flag3();
    var_dump($flag);
}

// - 03
require_once(__DIR__.'/flags_02.php');
function TestAdvanced() { 

    $flagword = TestFlags_Register();
    var_dump($flagword);

    $isset = TestFlags_Allset();
    var_dump($isset);
}