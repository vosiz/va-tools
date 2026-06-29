<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\HtmlElement;

class HtmlParagraph extends HtmlElement {

    /**
     * @param string $text Inner text
     */
    public function __construct(string $text = '') {

        parent::__construct('p', $text);
    }
}
