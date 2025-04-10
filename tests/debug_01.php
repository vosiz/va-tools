<?php

require_once(__DIR__.'/../src/debug/debug.php');

use \Vosiz\VaTools\Debug\Debugger as Debug;
use \Vosiz\Utils\Collections\Collection;

class Animal extends SmartObject {

    public $Name;
    private $Age;
}

class AnimalExt extends Animal {

    static public $Last;
}

function TestDebug_DumpAdvSmarto() {

    Debug::Dump(new Animal());
}

function TestDebug_DumpAdvCollection() {

    Debug::Dump(new Collection([56, 48, 55]));
}

function TestDebug_DumpAdvRecursion() {

    $last = new AnimalExt();
    $last::$Last = $last;
    Debug::Dump($last);
}
