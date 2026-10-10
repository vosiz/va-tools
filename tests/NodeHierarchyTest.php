<?php

use PHPUnit\Framework\TestCase;
use Vosiz\VaTools\Structure\NodeHierarchy;
use Vosiz\VaTools\Structure\NodeHierarchyException;

class NodeHierarchyTest extends TestCase {

    /** @test */
    public function SetParentAndAddChildEndInTheSameState() {

        $root_a = NodeHierarchy::Create('root_a');
        $root_b = NodeHierarchy::Create('root_b');
        $by_set = NodeHierarchy::Create('by_set');
        $by_add = NodeHierarchy::Create('by_add');

        $by_set->SetParent($root_a);
        $root_b->AddChild($by_add);

        $this->assertSame([$by_set], $root_a->GetChildren());
        $this->assertSame($root_a, $by_set->GetParent());
        $this->assertSame([$by_add], $root_b->GetChildren());
        $this->assertSame($root_b, $by_add->GetParent());
    }

    /** @test */
    public function SameParentAgainChangesNothing() {

        $root  = NodeHierarchy::Create('root');
        $child = NodeHierarchy::Create('child', $root);

        $child->SetParent($root);
        $root->AddChild($child);

        $this->assertSame([$child], $root->GetChildren());
    }

    /** @test */
    public function NewParentDetachesFromTheOldOne() {

        $first  = NodeHierarchy::Create('first');
        $second = NodeHierarchy::Create('second');
        $left   = NodeHierarchy::Create('left', $first);
        $moved  = NodeHierarchy::Create('moved', $first);
        $right  = NodeHierarchy::Create('right', $first);

        $second->AddChild($moved);

        $this->assertSame([$left, $right], $first->GetChildren());
        $this->assertSame([$moved], $second->GetChildren());
        $this->assertSame($second, $moved->GetParent());
        $this->assertSame(['first', 'left', 'right'], $this->Walk($first));
    }

    /** @test */
    public function RootFollowsTheWholeSubtree() {

        $tree   = NodeHierarchy::Create('tree');
        $branch = NodeHierarchy::Create('branch');
        $leaf   = NodeHierarchy::Create('leaf', $branch);

        $this->assertSame($branch, $leaf->GetRoot());

        $tree->AddChild($branch);

        $this->assertSame($tree, $branch->GetRoot());
        $this->assertSame($tree, $leaf->GetRoot());
        $this->assertSame($tree, $tree->GetRoot());
    }

    /** @test */
    public function ChildrenAddedLaterAreWalked() {

        $root  = NodeHierarchy::Create('root');
        $nodes = [NodeHierarchy::Create('a'), NodeHierarchy::Create('b')];
        NodeHierarchy::Create('under_a', $nodes[0]);

        $root->AddChildren($nodes);

        $this->assertSame(['root', 'a', 'under_a', 'b'], $this->Walk($root));
    }

    /** @test */
    public function NodeCannotGoUnderItself() {

        $node = NodeHierarchy::Create('node');

        $this->expectException(NodeHierarchyException::class);
        $node->SetParent($node);
    }

    /** @test */
    public function NodeCannotGoUnderItsDescendant() {

        $root  = NodeHierarchy::Create('root');
        $child = NodeHierarchy::Create('child', $root);
        $leaf  = NodeHierarchy::Create('leaf', $child);

        $this->expectException(NodeHierarchyException::class);
        $root->SetParent($leaf);
    }


    /**
     * Names of nodes in traversal order
     * @param NodeHierarchy $node where to start
     * @return array
     */
    private function Walk(NodeHierarchy $node) {

        $path = [];
        do {

            $path[] = $node->GetName();
            $node = $node->Next();

        } while($node);

        return $path;
    }
}
