<?php

namespace Vosiz\VaTools\Db;

class DbException extends \Exceptionf {

    /**
     * Contructor - format based
     */
    public function __construct(string $fmt, ...$args) {

        return parent::__construct($fmt, ...$args);
    }
}

class DbNotFoundException extends \Exception {

    public function __construct(string $db_name) {

        return parent::__construct("Database $db_name is missing.", 1049);
    }
}