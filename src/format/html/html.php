<?php

namespace VaTools\Format;

use \Vosiz\Utils\Collections\Dictionary;
use \VaTools\Format\Html\HtmlElement as Element;

require_once(__DIR__.'/inc.php');
require_once(__DIR__.'/factory.php');

class HtmlBuilder extends XmlBuilder {

    use HtmlFactory;

    protected $Head;    public function GetHead() { return $this->Head; }
    protected $Body;    public function GetBody() { return $this->Body; }


    /**
     * Creates generic HtmlElement for custom tags
     * @param string $tag Tag name
     * @param string $text Inner text
     * @param mixed $id Guid
     * @return VaTools\Format\Html\HtmlElement
     */
    public static function CreateElement(string $tag, string $text = '', $id = null) {

        return new Element($tag, $text, $id);
    }


    /**
     * Constructor — creates <html> root with <head> and <body>
     */
    public function __construct() {

        $this->Elements = new Dictionary();
        $this->Root = new Element('html');
        $this->Elements->Add($this->Root);

        $this->Head = new Element('head');
        $this->Head->SetParent($this->Root);

        $this->Body = new Element('body');
        $this->Body->SetParent($this->Root);
    }


    /**
     * Add element(s) to head
     * @param VaTools\Format\Html\HtmlElement|VaTools\Format\Html\HtmlElement[] $el
     * @return self
     */
    public function AddToHead($el) {

        foreach(asarray($el) as $e)
            $e->SetParent($this->Head);

        return $this;
    }

    /**
     * Add element(s) to body
     * @param VaTools\Format\Html\HtmlElement|VaTools\Format\Html\HtmlElement[] $el
     * @return self
     */
    public function AddToBody($el) {

        foreach(asarray($el) as $e)
            $e->SetParent($this->Body);

        return $this;
    }

    /**
     * Renders full HTML document
     * @return string
     */
    public function Render() {

        return '<!DOCTYPE html>'."\n".$this->Root->Render();
    }
}
