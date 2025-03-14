<?php

namespace Vosiz\VaTools\Filter;

interface IDataFilter {

    /**
     * Filters data
     * @param array $query_params parameters to query
     */
    public function Filter(array $query_params = array());
}