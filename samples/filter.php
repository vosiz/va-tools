<?php

require_once(__DIR__.'/../src/filter/strfilter.php');

// TEST
// - 00 - string filter
require_once(__DIR__.'/filter_00.php');
function TestStringFilter() {

    TestFilter_String_List();
    
    TestFilter_String_NoFilterAndList();
    
    $refs = TestFilter_String_Split();

    $filt = TestFilter_String_Filter_ContainsRefs();
    
    TestFilter_String_ListFiltered();
}