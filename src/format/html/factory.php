<?php

namespace VaTools\Format;

use \VaTools\Format\Html\HtmlElement as Element;
use \VaTools\Format\Html\Elements\HtmlDivision;
use \VaTools\Format\Html\Elements\HtmlHeading;
use \VaTools\Format\Html\Elements\HtmlLink;
use \VaTools\Format\Html\Elements\HtmlListItem;
use \VaTools\Format\Html\Elements\HtmlOrderedList;
use \VaTools\Format\Html\Elements\HtmlParagraph;
use \VaTools\Format\Html\Elements\HtmlTable;
use \VaTools\Format\Html\Elements\HtmlTr;
use \VaTools\Format\Html\Elements\HtmlTd;
use \VaTools\Format\Html\Elements\HtmlUnorderedList;

trait HtmlFactory {

    /**
     * @param string $text Inner text
     * @param string $href Href value
     * @return VaTools\Format\Html\Elements\HtmlLink
     */
    public static function A(string $text, string $href = '#') {

        return new HtmlLink($text, $href);
    }

    /**
     * @param string $text Inner text
     * @param string $type button|submit|reset
     * @return VaTools\Format\Html\HtmlElement
     */
    public static function Button(string $text = '', string $type = 'button') {

        $el = new Element('button', $text);
        $el->AddAtts(['type' => $type]);
        return $el;
    }

    /**
     * @param string $text Inner text
     * @return VaTools\Format\Html\Elements\HtmlDivision
     */
    public static function Div(string $text = '') {

        return new HtmlDivision($text);
    }

    /**
     * @param int $level Heading level 1–6
     * @param string $text Inner text
     * @return VaTools\Format\Html\Elements\HtmlHeading
     */
    public static function H(int $level, string $text = '') {

        return new HtmlHeading($level, $text);
    }

    /**
     * @param string $name Input name
     * @param string $value Input value
     * @return VaTools\Format\Html\HtmlElement
     */
    public static function Input(string $name, string $value = '') {

        $el = new Element('input');
        $el->AddAtts(['type' => 'text', 'name' => $name, 'value' => $value]);
        return $el;
    }

    /**
     * @param string $text Inner text
     * @return VaTools\Format\Html\Elements\HtmlListItem
     */
    public static function Li(string $text = '') {

        return new HtmlListItem($text);
    }

    /**
     * @param array $items Initial items as strings
     * @return VaTools\Format\Html\Elements\HtmlOrderedList
     */
    public static function Ol(array $items = []) {

        return new HtmlOrderedList($items);
    }

    /**
     * @param string $text Inner text
     * @return VaTools\Format\Html\Elements\HtmlParagraph
     */
    public static function P(string $text = '') {

        return new HtmlParagraph($text);
    }

    /**
     * @param array $data 2D array of cell values
     * @param array|null $headers Header labels; null = no thead
     * @return VaTools\Format\Html\Elements\HtmlTable
     */
    public static function Table(array $data, array $headers = null) {

        return new HtmlTable($data, $headers);
    }

    /**
     * @param array $cells 1D array of cell values
     * @return VaTools\Format\Html\Elements\HtmlTr
     */
    public static function Tr(array $cells = []) {

        $tr = new HtmlTr();
        foreach($cells as $cell) {

            $td = new HtmlTd(is_noe($cell) ? '' : (string)$cell);
            $td->SetParent($tr);
        }
        return $tr;
    }

    /**
     * @param array $items Initial items as strings
     * @return VaTools\Format\Html\Elements\HtmlUnorderedList
     */
    public static function Ul(array $items = []) {

        return new HtmlUnorderedList($items);
    }
}
