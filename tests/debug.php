<?php

// TEST
// - 00 - dumping
require_once(__DIR__.'/debug_00.php');
function TestDebugDumping() {

    TestDebug_DumpVarScalar();

    TestDebug_DumpVarObject();

    TestDebug_DumpVarArray();

    TestDebug_DumpVarMultiple();

    TestDebug_DumpVarMultipleComplex();

}

// - 01 - dumping advanced
require_once(__DIR__.'/debug_01.php');
function TestDebugDumpingAdv() {

    TestDebug_DumpAdvSmarto();

    TestDebug_DumpAdvCollection();

    TestDebug_DumpAdvRecursion();
}