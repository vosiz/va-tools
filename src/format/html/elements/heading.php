<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\HtmlElement;
use \VaTools\Format\Html\HtmlException;

class HtmlHeading extends HtmlElement {

    /**
     * @param int $level Heading level 1–6
     * @param string $text Inner text
     * @throws VaTools\Format\Html\HtmlException
     */
    public function __construct(int $level, string $text = '') {

        if($level < 1 || $level > 6)
            throw new HtmlException('heading', "level must be 1–6, got $level", new \InvalidArgumentException("Invalid heading level: $level"));

        parent::__construct('h'.$level, $text);
    }
}
