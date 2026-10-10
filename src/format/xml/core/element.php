<?php

namespace Vosiz\VaTools\Format\Xml;

use \Vosiz\Utils\Collections\Dictionary;
use \Vosiz\VaTools\Format\Xml\XmlAttribute;

class XmlElement {

    const NAME_PATTERN = '/^[A-Za-z_:][A-Za-z0-9_:.\-]*$/';

    protected $Guid;        public function GetGuid()       { return $this->Guid;       }
    protected $Name;        public function GetName()       { return $this->Name;       }   public function SetName($name)  { $this->Name = self::CheckName($name);  }
    protected $Text;        public function GetText()       { return $this->Text;       }   public function SetText($text)  { $this->Text = $text;  }
    protected $Atts;        public function GetAtts()       { return $this->Atts;       }       
    protected $Parent;      public function GetParent()     { return $this->Parent;     }
    protected $Children;    public function GetChildren()   { return $this->Children;   }


    /**
     * Checks element/attribute name
     * @param string $name
     * @return string checked name
     * @throws XmlException
     */
    public static function CheckName(string $name) {

        if(!preg_match(self::NAME_PATTERN, $name))
            throw new XmlException("Invalid name: %s", $name);

        return $name;
    }


    /**
     * Constructor
     * @param string $name Element name
     * @param string $text Inner text
     * @param mixed Identifikator (for search or reference), compared as collection key (my_id and MyId are the same)
     * @throws XmlException
     */
    public function __construct(string $name, string $text = '', $guid = null) {

        $this->Name = self::CheckName($name);
        $this->Text = $text;
        $this->Guid = $guid !== null ? $guid : \guid();
        $this->Children = new Dictionary();
        $this->Parent = null;
        $this->Atts = new Dictionary();
    }


    /**
     * Sets parent element, leaves the previous one
     * @param XmlElement $parent Parent element
     * @return self
     */
    public function SetParent(XmlElement $parent) {

        if($this->Parent === $parent)
            return $this;

        $this->Detach();
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
     * Adds attribute(s), existing one gets new value
     * @param string|array
     * @return self
     * @throws XmlException
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
            $this->Atts->Add($attr, $id, true);
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
        $s .= htmlspecialchars((string)$this->Text, ENT_NOQUOTES, 'UTF-8');
        foreach($this->Children as $c) {
            $s .= $c->Render();
        }
        $s .= $this->EndElement();

        return $s;
    }


    /**
     * Removes element from children of its parent
     */
    protected function Detach() {

        if($this->Parent === null)
            return;

        $this->Parent->Children->Remove($this->Guid, true);
        $this->Parent = null;
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
    protected function StartElement() {

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
    protected function EndElement() {

        if($this->IsVoided())
            return;

        return sprintf('</%s>', $this->Name);
    }

    /**
     * Check if element should be rendered as voided
     * @return bool <el></el> or <el />
     */
    protected function IsVoided() {

        return (is_noe($this->Text) && $this->Children->IsEmpty());
    }
}