<?php

use Vosiz\VaTools\Structure\Flagword as Fword;

function TestFlags_Size() {

    $fword = new Fword(10);
    return $fword->GetFlagwordUnitSize();
}

function TestFlags_Set() {

    $fword = new Fword(2);
    $fword->SetFlag(5);
    $fword->SetFlag(3, 0);

    $reads = array();
    for($i = 0; $i < $fword->GetFlagwordUnitSize() * 8; $i++) {

        $flag = $fword->ByIndex($i);
        $reads[$i] = $flag;
        
    }

    return $reads;
}
