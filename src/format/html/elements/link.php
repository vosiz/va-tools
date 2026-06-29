<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\Base\Clickable;

class HtmlLink extends Clickable {

    /**
     * @param string $text Inner text
     * @param string $href Href value
     */
    public function __construct(string $text, string $href = '#') {

        parent::__construct('a', $text);
        $this->AddAtts(['href' => $href]);
    }
}
