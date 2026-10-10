<?php

namespace Vosiz\VaTools\Parser;

class UrlParserException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }

    /**
     * Creates exception which keeps the original one as inner
     * @param \Throwable $inner original exception
     * @param string $fmt message format
     * @return UrlParserException
     */
    public static function CreateInner(\Throwable $inner, $fmt, ...$args) {

        $exc = new UrlParserException($fmt, ...$args);
        $exc->SetInner($inner);
        return $exc;
    }
}