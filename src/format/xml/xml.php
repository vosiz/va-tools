<?php

namespace Vosiz\VaTools\Format;

use \Vosiz\Utils\Collections\Dictionary;

require_once(__DIR__.'/exc.php');
require(__DIR__.'/core/element.php');
use \Vosiz\VaTools\Format\Xml\XmlElement as Element;
require(__DIR__.'/core/attribute.php');
use \Vosiz\VaTools\Format\Xml\XmlAttribute as Attribute;

class XmlBuilder {

    protected $Root;                public function GetRoot()           { return $this->Root;           }
    protected $Elements;            public function GetElements()       { return $this->Elements;       }
    protected $Declaration = true;  public function GetDeclaration()    { return $this->Declaration;    }   public function SetDeclaration(bool $on)    { $this->Declaration = $on; return $this;   }

    /**
     * Creates Root element
     * @param string $root_name Root node name
     * @return Vosiz\VaTools\Format\Xml\XmlElement
     */
    public static function CreateRoot(string $root_name = 'xml') {

        return new Element($root_name);
    }

    /**
     * Creates element
     * @param string $name Element id
     * @param string $text Inner text
     * @param mixed $id Guid, compared as collection key (my_id and MyId are the same)
     * @return Vosiz\VaTools\Format\Xml\XmlElement
     */
    public static function CreateElement(string $name, string $text = '', $id = null) {

        return new Element($name, $text, $id);
    }

    /**
     * Creates atribute
     * @param string $name Attribute id
     * @param array $values Attribute values
     * @return Vosiz\VaTools\Format\Xml\XmlAtribute
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
     * @param Vosiz\VaTools\Format\Xml\XmlElement $root New root node
     */
    public function SetRoot(Element $root) {

        $this->Elements = new Dictionary();
        $this->Root = $root;
        $this->Elements->Add($this->Root);
    }

    /**
     * Renders xml document, with XML declaration unless switched off
     * @return string Xml document as string
     */
    public function Render() {

        $s = $this->Declaration ? '<?xml version="1.0" encoding="UTF-8"?>'."\n" : '';
        foreach($this->Elements as $el) {

            $s .= $el->Render();
        }

        return $s;
    }

}