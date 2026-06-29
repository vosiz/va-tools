<?php

namespace VaTools\Format\Html;

use \VaTools\Format\Xml\XmlElement;
use \VaTools\Format\Xml\XmlAttribute;

class HtmlElement extends XmlElement {

    const VOID_TAGS = ['area', 'base', 'br', 'col', 'embed', 'hr', 'img', 'input', 'link', 'meta', 'param', 'source', 'track', 'wbr'];


    /**
     * Sets id attribute
     * @param string $id
     * @return self
     */
    public function SetId(string $id) {

        $this->AddAtts(['id' => $id]);
        return $this;
    }

    /**
     * Sets class attribute
     * @param string $class Space-separated class names
     * @return self
     */
    public function SetClass(string $class) {

        $this->AddAtts(['class' => $class]);
        return $this;
    }


    /**
     * Override: HTML void elements are void by tag name, not by content
     * @return bool
     */
    protected function IsVoided() {

        return in_array(strtolower($this->Name), self::VOID_TAGS);
    }

    /**
     * Override: HTML never uses self-closing />, always plain >
     * @return string
     */
    protected function StartElement() {

        $str = sprintf('<%s', $this->Name);
        $str .= $this->Atts->IsEmpty() ? '' : ' '.XmlAttribute::PrintArray($this->Atts);
        $str .= '>';

        return $str;
    }

    /**
     * Override: void tags have no closing tag; non-void always closes
     * @return string
     */
    protected function EndElement() {

        if($this->IsVoided())
            return '';

        return sprintf('</%s>', $this->Name);
    }
}
