<?php

use Vosiz\VaTools\Structure\Credentials;


function TestProtectedPass() {

    $cr = new Credentials('user', '1234');
    return $cr->GetPassword();
}

function TestPass() {

    $cr = new Credentials('user', '1234', true);
    return $cr->GetPassword();
}
