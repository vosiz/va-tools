<?php

namespace Vosiz\VaTools\Filter;

interface IPartable {

    /**
     * Value splitting
     * @param mixed $delimiter splitter
     */
    public function Split($delimiter);
}