<?php

namespace Vosiz\VaTools\Filter;

require_once(__DIR__.'/exc.php');
require_once(__DIR__.'/IDataFilter.php');
require_once(__DIR__.'/IPartable.php');
require_once(__DIR__.'/query.php');

use Vosiz\Utils\Collections\Collection;
use Vosiz\Enums\Enum as Enum;

abstract class DataFilter implements IDataFilter {

    protected $Data;            // original data
    protected $NullAllowed;     // flag if nulls are allowed as values
    protected $WorkingChunks;   // inner working buffer
    protected $References;      // references to master key
    protected $Filtered;        public function GetFiltered() { return $this->Filtered; }   // filtered data

    /**
     * Checks befory consistency check
     */
    abstract protected function ConsistencyPrecheck();

    /**
     * Construct
     * @param array $data_array input data
     * @param bool $allow_nulls allow nulas as values
     * @throws FilterException
     */
    public function __construct(array $data_array = array(), bool $allow_nulls = true) {

        $this->NullAllowed = $allow_nulls;

        if(!is_array($data_array))
            throw new FilterException("Input data is not an array");

        $this->Data = new Collection($data_array);
        $this->ConsistencyPrecheck();
        $this->ResetFilter();
    }

    /**
     * Prints raw input data
     * @param string $fmt print format
     */
    public function ListAll(string $fmt = "[%s]: %s</br>") {

        $this->PrintItems($this->Data->ToArray(), $fmt);
    }

    /**
     * Prints filtered data
     * @param string $fmt print format
     */
    public function ListFiltered(string $fmt = "[%s]: %s</br>") {

        $this->PrintItems($this->Filtered->ToArray(), $fmt);
    }

    /**
     * Prints refereces between entries
     * @param string $fmt print format
     */
    public function ListRefs(string $fmt = "%s refs %s</br>") {

        $this->PrintItems($this->References->ToArray(), $fmt);
    }

    /**
     * Resets filter and related things
     */
    public function ResetFilter() {

        $this->Filtered = new Collection();
        $i = 0; // incremental reindexing (MASTERKEY)
        foreach($this->Data->ToArray() as $entry) {

            $this->Filtered->Add($entry, $i++);
        }

        $this->References = new Collection();
        foreach($this->Filtered->ToArray() as $key => $val) {

            // preps references - value(s) references masterkey
            $refs = new Collection(toarray($val));
            $this->References->Add($refs, $key);
        }

        $this->WorkingChunks = NULL;
    }

    /**
     * Filters data with QueryParams
     * @param array $qp query params array
     * @throws FilterException
     */
    public function Filter(array $qp = array()) {

        try {

            // try to create query and cascades handler/commands/queries
            $q = new DataFilterQuery($qp);
            foreach($q->GetHandlers() as $handler) {

                $this->References = $handler->Execute($this->References);
                $this->ResoluteFiltration();
            }
            //$this->ListRefs();

        } catch(Exception $exc) {

            throw new FilterException("Failed to execute filter $handler->__toString() with error: $exc->getMessage()");
        }
    }

    /**
     * Check data type consistency
     * @param array[string|null] $allowed_types string types, include NULL to no check (leave)
     * @throws FilterException
     */
    protected function ConsistencyCheck(array $allowed_types = [NULL]) {

        if($this->Data === NULL)
            throw new FilterException("Data is null");

        if(!is_array($allowed_types))
            throw new FilterException("Invalid allowed types definition, variable is not array");

        // no check needed
        if($allowed_types = [NULL])
            return;

        foreach($this->Data->ToArray() as $item) {

            $type = typeof($item);
            if(!in_array($type, $allowed_types)) {

                if($this->NullAllowed && $type == "NULL")
                    continue;

                throw new FilterException("Data not consistent, found non allowed type %s", $type);
            }
        }
    }

    /**
     * Preps work-tools
     * @return Collection
     */
    protected function PrepWorking() {

        if(is_noe($this->WorkingChunks)) {

            $this->WorkingChunks = new Collection($this->Filtered->ToArray());
        }

        return $this->WorkingChunks;
    }

    /**
     * Prints built-in arrays
     * @param array $data array to print
     * @param string $fmt format string
     */
    protected function PrintItems(array $data = array(), string $fmt) {

        foreach($data as $key => $item) {

            echo sprintf($fmt, $key, tostr($item));
        }

    }

    /**
     * Filters data by references
     */
    protected function ResoluteFiltration() {

        $keeps = new Collection();
        foreach($this->References->ToArray() as $keepkey => $value) {

            $keeps->Add($this->Data->{$keepkey}, $keepkey);
        }

        $this->Filtered->RemoveExcept($keeps->ToArray(), true);
    }

}