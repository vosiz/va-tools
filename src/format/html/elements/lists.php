<?php

namespace VaTools\Format\Html\Elements;

class HtmlOrderedList extends HtmlList {

    /**
     * @param array $items Initial items as strings
     */
    public function __construct(array $items = []) {

        parent::__construct(true, $items);
    }
}


class HtmlUnorderedList extends HtmlList {

    /**
     * @param array $items Initial items as strings
     */
    public function __construct(array $items = []) {

        parent::__construct(false, $items);
    }
}
