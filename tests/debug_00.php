<?php

require_once(__DIR__.'/../src/debug/debug.php');

use \Vosiz\VaTools\Debug\Debugger as Debug;

class SimpleClass {

    public      $PublicVar = "this is public";
    protected   $ProtectedVar = "this is protected";
    private     $PrivateVar = "this is private";
}

class ComplexClass {

    static $static = "this is static";
    public $sc;
    public $par = "NULL";

    public function __construct($par = "something") {

        $this->sc = new SimpleClass();
        $this->par = $par;
    }
}


function TestDebug_DumpVarScalar() {

    $var = 1;
    Debug::Dump($var);
    $var = 1.2;
    Debug::Dump($var);
    $var = "stringy thingy";
    Debug::Dump($var);
    $var = FALSE;
    Debug::Dump($var);
    $var = NULL;
    Debug::Dump($var);
    
}

function TestDebug_DumpVarObject() {

    $var = new SimpleClass();
    Debug::Dump($var); 
}

function TestDebug_DumpVarArray() {

    $var = [1, 2, 3];
    Debug::Dump($var); 
    $var = [1, 2.5, "stringy", NULL];
    Debug::Dump($var); 
    $var = [1 => 50, 'key_69' => new SimpleClass()];
    Debug::Dump($var); 
    $var = [
        1 => 1,
        [
            1 => 2,
        ],
    ];
    Debug::Dump($var);
}

function TestDebug_DumpVarMultiple() {

    $var1 = $var2 = $var3 = [1, 2, 3];
    Debug::Dump($var1, $var2, $var3);
}

function TestDebug_DumpVarMultipleComplex() {

    $var1 = new ComplexClass("foo");
    $var2 = [
        1 => 1,
        [
            1 => $var1,
        ],
    ];
    Debug::Dump($var1, $var2);
}