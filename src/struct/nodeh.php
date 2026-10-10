<?php

namespace Vosiz\VaTools\Structure;

class NodeHierarchyException extends \Exception {

    /**
     * Basic constructor
     * @param string $msg message
     */
    public function __construct(string $msg) {

        return parent::__construct($msg);
    }
}

class NodeHierarchy {

    protected $Guid;                public function GetGuid()       { return $this->Guid; }
    protected $Name;                public function GetName()       { return $this->Name; }
    protected $Parent = NULL;       public function GetParent()     { return $this->Parent; }
    protected $Children = [];       public function GetChildren()   { return $this->Children; }
    protected $Properties = [];     public function GetProperties() { return $this->Properties; }
    protected $Root = NULL;         public function GetRoot()       { return $this->Root; }

    /**
     * Constructor - protected
     * @param string $name Identifaction
     * @param NodeHierarchy $parent Parent node (null means it will be root)
     */
    protected function __construct(string $name, ?NodeHierarchy $parent = NULL) {

        $this->Name = $name;
        $this->Root = $this;
        $this->Guid = \guid();

        if(!is_null($parent)) {

            $this->SetParent($parent);
        }
    }

    /** 
     * Sets parent, leaves the previous one - node becomes a child of the new parent
     * @param NodeHierarchy $node Node to set as parent
     * @return NodeHierarchy
     * @throws NodeHierarchyException
    */
    public function SetParent(NodeHierarchy $node) {

        if($this->Parent === $node)
            return $this;

        if($node->IsWithin($this))
            throw new NodeHierarchyException("Node {$this->Name} cannot be placed under itself or its own descendant");

        $this->Detach();
        $this->Parent = $node;
        $node->Children[] = $this;
        $this->UpdateRoot($node->Root);

        return $this;
    }

    /** 
     * Adds child node - the same as setting this node as its parent
     * @param NodeHierarchy $node Child node
     * @return NodeHierarchy
     * @throws NodeHierarchyException
    */
    public function AddChild(NodeHierarchy $node) {

        $node->SetParent($this);
        return $this;
    }

    /** 
     * Adds multiple children nodes
     * @param NodeHierarchy[] $nodes Nodes to add
     * @return NodeHierarchy
    */
    public function AddChildren(array $nodes = array()) {

        foreach($nodes as $node) {

            if(!$node instanceof NodeHierarchy)
                throw new NodeHierarchyException("Node is not NodeHierarchy type, found: {$node->__toString()}");

            $this->AddChild($node);
        }
        return $this;
    }

    /** 
     * Adds property
     * @param string $section Group id
     * @param string $key Key identification
     * @param string $value Value
     * @return NodeHierarchy
    */
    public function AddProperty(string $section, string $key, string $value) {

        if(!array_key_exists($section, $this->Properties)) {

            $this->Properties[$section] = array();
        }

        $this->Properties[$section][$key] = $value;
        return $this;
    }

    /** 
     * Traversal function - giving next node
     * @return NodeHierarchy
    */
    public function Next() {

        if (!empty($this->Children)) {
            return $this->Children[0];
        }

        $current = $this;
        while ($current->Parent !== null) {

            $siblings = $current->Parent->GetChildren();
            $index = array_search($current, $siblings, true);

            if ($index !== false && isset($siblings[$index + 1])) {
                return $siblings[$index + 1]; 
            }

            $current = $current->Parent;
        }

        return null;
    }

    /**
     * Instantiate
     * @param string $name name of node
     * @param NodeHierarchy $parent parent node, NULL if root
     * @return NodeHierarchy
     */
    public static function Create(string $name, ?NodeHierarchy $parent = NULL) {

        return new NodeHierarchy($name, $parent);
    }


    /**
     * Removes node from children of its parent
     */
    protected function Detach() {

        if($this->Parent === NULL)
            return;

        $index = array_search($this, $this->Parent->Children, true);
        if($index !== false) {

            unset($this->Parent->Children[$index]);
            $this->Parent->Children = array_values($this->Parent->Children);
        }

        $this->Parent = NULL;
    }


    /**
     * Checks if node is given node or lies somewhere under it
     * @param NodeHierarchy $node
     * @return bool
     */
    private function IsWithin(NodeHierarchy $node) {

        $current = $this;
        while($current !== NULL) {

            if($current === $node)
                return true;

            $current = $current->Parent;
        }

        return false;
    }

    /**
     * Sets root for node and its whole subtree
     * @param NodeHierarchy $root
     */
    private function UpdateRoot(NodeHierarchy $root) {

        $this->Root = $root;
        foreach($this->Children as $child) {

            $child->UpdateRoot($root);
        }
    }
}