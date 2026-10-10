<?php

use Vosiz\VaTools\Filter\StringFilter;

$filt = new StringFilter(classlist());

function TestFilter_String_List() {

    global $filt;
    $filt->ListAll();
    $filt->ListRefs();
    return $filt;
}

function TestFilter_String_NoFilterAndList() {

    global $filt;
    $filt->ListFiltered();
    return $filt;
}

function TestFilter_String_Split() {

    global $filt;
    $filt->Split("\\");
    $filt->ListRefs();
    return $filt;
}

function TestFilter_String_Filter_ContainsRefs() {

    global $filt;
    $filt->Filter(['contains' => 'Vosiz']);
    $filt->ListRefs();
    return $filt;
}

function TestFilter_String_ListFiltered() {

    global $filt;
    echo "Filtered<br>";
    $filt->ListFiltered();
    return $filt;
}