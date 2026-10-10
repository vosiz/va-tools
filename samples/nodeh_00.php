<?php

use Vosiz\VaTools\Structure\NodeHierarchy as Nodeh;

function TestNodeh_Root() {

    $root = Nodeh::Create("ROOT");
    return $root;
}

function TestNodeh_BasicGraph() {

    $root = Nodeh::Create("ROOT");
    $node = Nodeh::Create("node", $root);
    return $root;
}

function TestNodeh_ChildrenLater() {

    $root = Nodeh::Create("ROOT");
    $nodes = [];
    for($i = 0; $i < 3; $i++) {

        $nodes[] = Nodeh::Create('Node_'.$i);
    }

    $root->AddChildren($nodes);
    return $root;
}