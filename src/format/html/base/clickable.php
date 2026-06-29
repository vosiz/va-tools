<?php

namespace VaTools\Format\Html\Base;

use \VaTools\Format\Html\HtmlElement;

class Clickable extends HtmlElement {

    /**
     * Sets href attribute
     * @param string $href
     * @return self
     */
    public function SetLink(string $href) {

        $this->AddAtts(['href' => $href]);
        return $this;
    }
}
