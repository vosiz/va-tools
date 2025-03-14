<?php

namespace Vosiz\VaTools\Filter;

use Vosiz\Utils\Collections\Collection;


class DataFilterQueryCommandTranslation {

    private static $CommandTranslations = [

        'contains'  => 'Existance',
    ];

    /**
     * Translate string to query command
     * @param string $command string to translate
     * @return string Command name function
     */
    public static function Translate(string $command) {

        try {

            return self::$CommandTranslations[$command];

        } catch (Exception $exc) {

            throw new FilterException("Command $command cannot be translated to query handler");
        }
    }
}


abstract class DataFilterQueryHandler {

    private $Command;
    private $Pars;

    /**
     * Construct
     * @param string $cmd method name
     * @param array $pars parameters for command
     */
    public function __construct(string $cmd, $pars = array()) {

        $this->Command = $cmd;
        $this->Pars = $pars;
    }

    /**
     * Executes command
     * @param Collection $data input data
     */
    public function Execute(Collection $data) {

        try {

            return $this->{\str_camel($this->Command)}($data, $this->Pars);

        } catch (Exception $exc) {

            throw new FilterException("Failed to execute command in QueryHandler: $exc->__toString()");
        }

        
    }
}