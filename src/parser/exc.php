<?php

namespace Vosiz\VaTools\Parser;

class UrlParserException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}