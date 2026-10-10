<?php

use Vosiz\VaTools\Structure\Credentials;

$cr = new Credentials('user', '1234');

function TestAuthBothBad() {

    global $cr;
    return $cr->Auth('us', 'xx');
}

function TestAuthUserBad() {

    global $cr;
    return $cr->Auth('us', '1234');
}

function TestAuthPassBad() {

    global $cr;
    return $cr->Auth('user', 'xx');
}

function TestAuth() {

    global $cr;
    return $cr->Auth('user', '1234');
}
