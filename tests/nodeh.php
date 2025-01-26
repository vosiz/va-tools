<?php

require_once(__DIR__.'/../src/struct/nodeh.php');
use \VaTools\Structure\NodeHierarchy as Nodeh;


// TEST
// - 00
require_once(__DIR__.'/nodeh_00.php');
function TestBasics() {

    $node = TestNodeh_Root();
    var_dump($node);

    $node = TestNodeh_BasicGraph();
    var_dump($node);

    $node = TestNodeh_ChildrenLater();
    var_dump($node);
}

// - 01
require_once(__DIR__.'/nodeh_01.php');
function TestAdvanced() {

    $node = TestNodeh_Property();
    var_dump($node);

    $node = TestNodeh_Hierarchy();
    var_dump($node);

}