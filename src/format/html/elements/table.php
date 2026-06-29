<?php

namespace VaTools\Format\Html\Elements;

use \VaTools\Format\Html\HtmlElement;
use \VaTools\Format\Html\TableInvalidDimensions;

class HtmlThead extends HtmlElement {

    public function __construct() {

        parent::__construct('thead');
    }
}


class HtmlTbody extends HtmlElement {

    public function __construct() {

        parent::__construct('tbody');
    }
}


class HtmlTable extends HtmlElement {

    protected $ColCount = 0;
    protected $Thead = null;
    protected $Tbody;


    /**
     * @param array $data 2D array of cell values
     * @param array|null $headers Header labels; null = no thead
     */
    public function __construct(array $data, array $headers = null) {

        parent::__construct('table');

        if($headers !== null)
            $this->ColCount = count($headers);
        else if(!empty($data))
            $this->ColCount = count(reset($data));

        if($headers !== null)
            $this->SetHeaders($headers);

        $this->Tbody = new HtmlTbody();
        $this->Tbody->SetParent($this);

        foreach($data as $row)
            $this->AddRow($this->MakeRow($row));
    }


    /**
     * Sets or replaces the thead — validates column count
     * @param array $headers
     * @return self
     * @throws VaTools\Format\Html\TableInvalidDimensions
     */
    public function SetHeaders(array $headers) {

        if($this->ColCount > 0 && count($headers) !== $this->ColCount)
            throw new TableInvalidDimensions('thead', $this->ColCount, count($headers));

        if($this->ColCount === 0)
            $this->ColCount = count($headers);

        $thead = new HtmlThead();
        $tr = new HtmlTr();
        $tr->SetParent($thead);

        foreach($headers as $h) {

            $th = new HtmlTh(is_noe($h) ? '' : (string)$h);
            $th->SetParent($tr);
        }

        if($this->Tbody === null) {

            $thead->SetParent($this);

        } else {

            $thead->Parent = $this;
            $old = [];
            foreach($this->Children as $c) $old[] = $c;
            $this->Children->Clear();
            $this->Children->Add($thead, $thead->GetGuid());
            foreach($old as $c) $this->Children->Add($c, $c->GetGuid());
        }

        $this->Thead = $thead;
        return $this;
    }

    /**
     * Appends a HtmlTr to tbody — validates column count
     * @param HtmlTr $tr
     * @return self
     * @throws VaTools\Format\Html\TableInvalidDimensions
     */
    public function AddRow(HtmlTr $tr) {

        if($this->ColCount > 0 && $tr->ChildrenCount() !== $this->ColCount)
            throw new TableInvalidDimensions('tr', $this->ColCount, $tr->ChildrenCount());

        $tr->SetParent($this->Tbody);
        return $this;
    }


    /**
     * Creates HtmlTr with HtmlTd children from a 1D array
     * @param array $row
     * @return HtmlTr
     * @throws VaTools\Format\Html\TableInvalidDimensions
     */
    private function MakeRow(array $row) {

        if($this->ColCount > 0 && count($row) !== $this->ColCount)
            throw new TableInvalidDimensions('table', $this->ColCount, count($row));

        $tr = new HtmlTr();
        foreach($row as $cell) {

            $td = new HtmlTd(is_noe($cell) ? '' : (string)$cell);
            $td->SetParent($tr);
        }

        return $tr;
    }
}
