<?php

use Vosiz\VaTools\Structure\Flagword as Fword;


function TestFlags_Register() { 

    $fword = new Fword(10);

    $fword->RegisterFlag(13);
    $fword->RegisterFlag(5, "Some key");
    $fword->RegisterFlag(8, 'another');
    $fword->RegisterFlag(12, "Updated");
    $fword->RegisterFlag(12, "Updated", true);
    
    return $fword;
}

function TestFlags_Allset() {

    $fword = new Fword(10);

    $fword->RegisterFlag(8);
    $fword->RegisterFlag(7, "seven");

    $fword->SetFlag(8);
    $fword->SetFlag(7);

    return $fword->IsAllSet();       
}
