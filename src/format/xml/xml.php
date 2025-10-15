<?php

namespace VaTools\Format;

use \Vosiz\Utils\Collections\Dictionary;

require(__DIR__.'/core/element.php');
use \VaTools\Format\Xml\XmlElement as Element;
require(__DIR__.'/core/attribute.php');
use \VaTools\Format\Xml\XmlAttribute as Attribute;

class XmlBuilder {

    protected $Root;        public function GetRoot()       { return $this->Root;       }
    protected $Elements;    public function GetElements()   { return $this->Elements;   }

    /**
     * Creates Root element
     * @param string $root_name Root node name
     * @return VaTools\Format\Xml\XmlElement
     */
    public static function CreateRoot(string $root_name = 'xml') {

        return new Element($root_name);
    }

    /**
     * Creates element
     * @param string $name Element id
     * @param string $text Inner text
     * @param mixed $id Guid
     * @return VaTools\Format\Xml\XmlElement
     */
    public static function CreateElement(string $name, string $text = '', $id = null) {

        return new Element($name, $text, $id);
    }

    /**
     * Creates atribute
     * @param string $name Attribute id
     * @param array $values Attribute values
     * @return VaTools\Format\Xml\XmlAtribute
     */
    public static function CreateAttribute(string $name, array $values = []) {

        return new Attribute($name, $values);
    }


    /**
     * Constructor
     * @param string $root_name
     */
    public function __construct(string $root_name = 'xml') {

        $this->Elements = new Dictionary();
        $this->Root = new Element($root_name);
        $this->Elements->Add($this->Root);
    }


    /**
     * Set new root element
     * @param VaTools\Format\Xml\XmlElement $root New root node
     */
    public function SetRoot(Element $root) {

        $this->Elements = new Dictionary();
        $this->Root = $root;
        $this->Elements->Add($this->Root);
    }

    /**
     * Renders xml document
     * @return string Xml document as string
     */
    public function Render() {

        $s = '';
        foreach($this->Elements as $el) {

            $s .= $el->Render();
        }

        return $s;
    }


    /** TODO:
     * Add children node
     * @param VaTools\Format\Xml\XmlElement $el 
     */
    private function AddChild(Element $el) {

        foreach($el->GetChildren() as $c) {

            $this->Add($c);
            if($c->ChildrenCount())
                $this->AddChild();
        }
    }

    /**
     * Add new element
     * @param VaTools\Format\Xml\XmlElement $el Element to add
     */
    private function Add(Element $el) {

        $this->Elements->Add($el, $el->GetGuid(), true);
    }
    
}