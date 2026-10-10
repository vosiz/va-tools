<?php

require_once(__DIR__.'/../src/struct/cred.php');

// TEST
// - 00 - password string
require_once(__DIR__.'/creds_00.php');
function TestCredGetPass() { 

    $pass = TestProtectedPass();
    var_dump($pass);

    $pass = TestPass();
    var_dump($pass);
}


// - 01 - authorization
require_once(__DIR__.'/creds_01.php');
function TestCredAuth() {

    $passed = TestAuthBothBad();
    var_dump($passed);
    
    $passed = TestAuthUserBad();
    var_dump($passed);
    
    $passed = TestAuthPassBad();
    var_dump($passed);
    
    $passed = TestAuth();
    var_dump($passed);
}