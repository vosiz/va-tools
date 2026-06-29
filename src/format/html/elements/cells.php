<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\HtmlElement;

class HtmlTr extends HtmlElement {

    public function __construct() {

        parent::__construct('tr');
    }
}


class HtmlTd extends HtmlElement {

    /**
     * @param string $text Inner text
     */
    public function __construct(string $text = '') {

        parent::__construct('td', $text);
    }
}


class HtmlTh extends HtmlElement {

    /**
     * @param string $text Inner text
     */
    public function __construct(string $text = '') {

        parent::__construct('th', $text);
    }
}
