<?php

use \VaTools\Structure\NodeHierarchy as Nodeh;

function TestNodeh_Property() {

    $root = Nodeh::Create("ROOT");
    $root->AddProperty('general', 'width', 128);
    $props = $root->GetProperties();
    return $props;
}


function TestNodeh_Hierarchy() {

    $root = Nodeh::Create("ROOT");
    $node_left1 = Nodeh::Create("node_L1", $root);
    $node_left2 = Nodeh::Create("node_L2", $node_left1);
    $node_right1 = Nodeh::Create("node_R1", $root);

    $path = [];
    $node = $root;
    do {
        $path[] = $node->GetName();
        $node = $node->Next();
    }
    while($node);

    return $path;
}