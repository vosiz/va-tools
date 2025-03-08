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
    protected function __construct(string $name, NodeHierarchy $parent = NULL) {

        $this->Name = $name;
        $this->Root = $this;
        if(!is_null($parent)) {

            $parent->AddChild($this);
            $this->Root = $parent->Root;
        }
         
        $this->Guid = com_create_guid();
    }

    /** 
     * Sets parent
     * @param NodeHierarchy $node Node to set as parent
     * @return NodeHierarchy
    */
    public function SetParent(NodeHierarchy $node) {

        $this->Parent = $node;
        return $this;
    }

    /** 
     * Adds child node
     * @param NodeHierarchy $node Child node
     * @return NodeHierarchy
    */
    public function AddChild(NodeHierarchy $node) {

        $this->Children[] = $node;
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
                throw new NodeHierarchyException("Node is not NodeHierarchy type, found: $node->__toString()");

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
    public static function Create(string $name, NodeHierarchy $parent = NULL) {

        return new NodeHierarchy($name, $parent);
    }
}