<?php

namespace Vosiz\VaTools\Filter;

require_once(__DIR__.'/filter.php');


use Vosiz\Utils\Collections\Collection;

class StringFilter extends DataFilter implements IPartable {

    /**
     * Constructor - child
     */
    public function __construct($str_array_data = array(), $allow_nulls = true) {

        parent::__construct($str_array_data, $allow_nulls);
    }

    /**
     * Splits data string values
     * @param mixed|string $delimiter string splitter
     * @param FilterException
     */
    public function Split($delimiter) {

        if(!is_string($delimiter))
            throw new FilterException("Split delimiter is not a string, %s type found", \typeof($delimiter));

        $data = $this->PrepWorking();

        $split = new Collection();
        foreach($data->ToArray() as $index => $str) {

            $parts = explode($delimiter, $str);
            foreach($parts as $str_ind => $part) {

                $split->Add($part, NULL, true);
                $refs = $this->References->{$index};
                if(count($parts) > 1)
                    $refs->Add($part);
            }
        }
        $this->WorkingChunks = $split;
    }

    /**
     * Checks if data are string only
     */
    protected function ConsistencyPrecheck() {

        $this->ConsistencyCheck(['string']);
    }

}