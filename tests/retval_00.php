<?php

require_once(__DIR__.'/../src/shorty.php');

use Vosiz\VaTools\RetvalType;
use Vosiz\VaTools\Retval;

function TestRetval_Type() {

    return RetvalType::GetEnum('fakup');
}

function TestRetval_Create() {

    return Retval::Create('warning', 'not a %s problem', 'severe');
}

function TestRetval_ToString() {

    return Retval::Create('fail', 'failed for some reason')->ToString();
}

function TestRetval_CompareGood() {

    $info = Retval::Create('info', 'infomation');
    return $info->Is('info');
}

function TestRetval_CompareBad() {

    $info = Retval::Create('info', 'infomation');
    return $info->Is('fail');
}

function TestRetval_CreateSpecifics() {

    $msg = "something";
    $all = [];
    $all[] = retval_success($msg)->ToString();
    $all[] = retval_notice($msg)->ToString();
    $all[] = retval_info($msg)->ToString();
    $all[] = retval_warning($msg)->ToString();
    $all[] = retval_fail($msg)->ToString();
    $all[] = retval_error($msg)->ToString();
    $all[] = retval_fatal($msg)->ToString();
    $all[] = retval_exception($msg)->ToString();
    $all[] = retval_fakup($msg)->ToString();

    return $all;
}

function TestRetval_CreateSpecificsLiteral() {

    $fmt = "something %s";
    $par = "'retval'";
    $all = [];
    $all[] = Retval::Success($fmt, $par);
    $all[] = Retval::Notice($fmt, $par);
    $all[] = Retval::Info($fmt, $par);
    $all[] = Retval::Warning($fmt, $par);
    $all[] = Retval::Fail($fmt, $par);
    $all[] = Retval::Error($fmt, $par);
    $all[] = Retval::Gatal($fmt, $par);
    $all[] = Retval::Exception($fmt, $par);
    $all[] = Retval::Debug($fmt, $par);
    $all[] = Retval::Fakup($fmt, $par);

    return $all;
}