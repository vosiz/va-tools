<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\HtmlElement;

class HtmlList extends HtmlElement {

    /**
     * @param bool $ordered Ordered (ol) or unordered (ul)
     * @param array $items Initial items as strings
     */
    public function __construct(bool $ordered, array $items = []) {

        parent::__construct($ordered ? 'ol' : 'ul');

        foreach($items as $text)
            $this->AddItem($text);
    }


    /**
     * Prepends a HtmlListItem to the beginning of the list
     * @param HtmlListItem $item
     * @return self
     */
    public function SetFirst(HtmlListItem $item) {

        $item->Parent = $this;

        $old = [];
        foreach($this->Children as $c)
            $old[] = $c;

        $this->Children->Clear();
        $this->Children->Add($item, $item->GetGuid());

        foreach($old as $c)
            $this->Children->Add($c, $c->GetGuid());

        return $this;
    }

    /**
     * Appends a HtmlListItem to the end of the list
     * @param HtmlListItem $item
     * @return self
     */
    public function SetLast(HtmlListItem $item) {

        $item->SetParent($this);
        return $this;
    }

    /**
     * Creates and appends a new HtmlListItem from string
     * @param string $text
     * @return self
     */
    public function AddItem(string $text) {

        $item = new HtmlListItem($text);
        $item->SetParent($this);
        return $this;
    }
}
