<?php

namespace Vosiz\VaTools\Db;

class DbException extends \Exceptionf {

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
     * @return DbException
     */
    public static function CreateInner(\Throwable $inner, $fmt, ...$args) {

        $exc = new DbException($fmt, ...$args);
        $exc->SetInner($inner);
        return $exc;
    }
}

class DbNotFoundException extends \Exception {

    public function __construct(string $db_name) {

        return parent::__construct("Database $db_name is missing.", 1049);
    }
}