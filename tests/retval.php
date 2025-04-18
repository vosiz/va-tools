<?php

// TEST
// - 00
require_once(__DIR__.'/retval_00.php');
function TestBasics() {

    $retval = TestRetval_Type();
    var_dump($retval);

    $retval = TestRetval_Create();
    var_dump($retval);

    $str = TestRetval_ToString();
    var_dump($str);

    $good = TestRetval_CompareGood();
    var_dump($good);

    $bad = TestRetval_CompareBad();
    var_dump($bad);

    $all = TestRetval_CreateSpecifics();
    var_dump($all);
}