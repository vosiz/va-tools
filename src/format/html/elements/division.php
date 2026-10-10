<?php

namespace Vosiz\VaTools\Format\Html\Elements;

use \Vosiz\VaTools\Format\Html\HtmlElement;

class HtmlDivision extends HtmlElement {

    /**
     * @param string $text Inner text
     */
    public function __construct(string $text = '') {

        parent::__construct('div', $text);
    }
}
