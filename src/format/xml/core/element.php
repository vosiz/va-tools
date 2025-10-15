<?php

namespace VaTools\Format\Xml;

use \Vosiz\Utils\Collections\Dictionary;
use \VaTools\Format\Xml\XmlAttribute;

class XmlElement {

    private $Guid;      public function GetGuid()       { return $this->Guid;       }
    private $Name;      public function GetName()       { return $this->Name;       }
    private $Text;      public function GetText()       { return $this->Text;       }
    private $Atts;      public function GetAtts()       { return $this->Atts;       }       
    private $Parent;    public function GetParent()     { return $this->Parent;     }
    private $Children;  public function GetChildren()   { return $this->Children;   }


    /**
     * Constructor
     * @param string $name Element name
     * @param string $text Inner text
     * @param mixed Identifikator (for search or reference)
     */
    public function __construct(string $name, string $text = '', $guid = null) {

        $this->Name = $name;
        $this->Text = $text;
        $this->Guid = $guid ? $guid : com_create_guid();
        $this->Children = new Dictionary();
        $this->Parent = null;
        $this->Atts = new Dictionary();
    }


    /**
     * Sets parent element
     * @param XmlElement $parent Parent element
     * @return self
     */
    public function SetParent(XmlElement $parent) {

        $this->Parent = $parent;
        $parent->AddChild($this);

        return $this;
    }

    /**
     * Return children count
     * @return int count
     */
    public function ChildrenCount() {

        return $this->Children->Count();
    }

    /**
     * Adds attribute(s)
     * @param string|array
     * @return self
     */
    public function AddAtts($atts) {

        if(!is_array($atts)) {

            $key = $atts;
            $atts = [];
            $atts[$key] = null; // void
        }
        
        foreach($atts as $id => $vals) {

            $vals = asarray($vals);
            $attr = new XmlAttribute($id, $vals);
            $this->Atts->Add($attr, $id);
        }

        return $this;
    }

    /**
     * Renders as string
     * @return string
     */
    public function Render() {

        $s = '';
        $s .= $this->StartElement();
        $s .= $this->Text;
        foreach($this->Children as $c) {
            $s .= $c->Render();
        }
        $s .= $this->EndElement();

        return $s;
    }


    /**
     * Adds child(ren)
     * @param XmlElement|XmlElement[] $children
     * @return self
     */
    private function AddChild($children) {

        $children = asarray($children);
        foreach($children as $c) {

            $this->Children->Add($c, $c->GetGuid());
        }

        return $this;
    }

    /**
     * Element render start
     * @return string
     */
    private function StartElement() {

        $str = sprintf('<%s', $this->Name);
        $str .= $this->Atts->IsEmpty() ? '' : ' '.XmlAttribute::PrintArray($this->Atts);

        if($this->IsVoided())
            $str .='/';
        $str .= '>';

        return $str;
    }

    /**
     * Element render end
     * @return false when void
     * @return string when two part element
     */
    private function EndElement() {

        if($this->IsVoided())
            return;

        return sprintf('</%s>', $this->Name);
    }

    /**
     * Check if element should be rendered as voided
     * @return bool <el></el> or <el />
     */
    private function IsVoided() {

        return (is_noe($this->Text) && empty($this->Children));
    }
}