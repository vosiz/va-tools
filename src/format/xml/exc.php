<?php

namespace Vosiz\VaTools\Format\Xml;

class XmlException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}
