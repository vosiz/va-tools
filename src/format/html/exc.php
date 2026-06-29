<?php

namespace VaTools\Format\Html;

class HtmlException extends \Exception {

    protected $Element;    public function GetElement() { return $this->Element; }


    /**
     * @param string $element Element context where the exception occurred
     * @param string $message Error message
     * @param \Throwable|null $previous Inner exception
     */
    public function __construct(string $element, string $message = '', \Throwable $previous = null) {

        $this->Element = $element;
        parent::__construct(sprintf('[%s] %s', $element, $message), 0, $previous);
    }
}


class TableInvalidDimensions extends HtmlException {

    /**
     * @param string $element Context element (table, tr, thead)
     * @param int $expected Expected column count
     * @param int $actual Actual column count received
     */
    public function __construct(string $element, int $expected, int $actual) {

        parent::__construct(
            $element,
            sprintf('expected %d column(s), got %d', $expected, $actual),
            new \InvalidArgumentException(sprintf('expected %d column(s), got %d', $expected, $actual))
        );
    }
}
