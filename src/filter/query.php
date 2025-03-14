<?php

namespace Vosiz\VaTools\Filter;


require_once(__DIR__.'/query/base.php');

class DataFilterQuery {

    private $QueryHandlers = [];      public function GetHandlers() {  return $this->QueryHandlers; }

    /**
     * Constructor
     * @param array $qp query parameters
     */
    public function __construct(array $qp = array()) {

        try {

            foreach($qp as $command => $params) {

                if(!is_array($params))
                    $params = toarray($params);

                $this->QueryHandlers[] = $this->TryParse($command, $params);
            }

        } catch(Exception $exc) {

            throw $exc;
        }
    }

    /**
     * Tries to parse parameters and create handler
     * @param string $command command
     * @param array $pars parameters
     */
    private function TryParse(string $command, array $pars = array()) {

        try {

            $class = DataFilterQueryCommandTranslation::Translate($command);

            require_once(__DIR__.'/query/'.strtolower($class).'.php');

            $class = 'Vosiz\\VaTools\\Filter\\'.$class;

            return \instclass($class, $command, $pars);

        } catch (Exception $exc) {

            throw new FilterException("Cannot parse query: ".$exc->getMessage());
        }
    }
}