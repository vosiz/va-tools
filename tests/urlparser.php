<?php

require_once(__DIR__.'/../src/parser/url.php');

// TEST
// - 00
require_once(__DIR__.'/urlparser_00.php');
function TestBasics() {

    $test = TestUrlParser_Structure();
    var_dump($test);

    $test = TestUrlParser_Parsing();
    var_dump($test);

    $test = TestUrlParser_GetPars();
    var_dump($test);

    $test = TestUrlParser_NoKeys();
    var_dump($test);

    $test = TestUrlParser_GetNonExisting();
    var_dump($test);

    $test = TestUrlParser_TooShort();
    var_dump($test);
}