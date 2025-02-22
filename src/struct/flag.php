<?php

namespace VaTools\Structure;

use Vosiz\Utils\Collections\Collection as Collection;

class FlagException extends \Exception {

    public function __construct($msg) {

        return parent::__construct("FlagException: ".$msg);
    }
}

class Flag {

    private $Set = 0;   public function IsSet()     { return int2b($this->Set); }
    private $Key;       public function GetKey()    { return $this->Key; }          public function SetKey($key)    { $this->Key = $key; }

    /**
     * Constructor
     * @param bool $setflag is initial value 1?
     */
    public function __construct(bool $setflag = false) {

        if($setflag)
            $this->Set();
    }

    /**
     * Set flag
     * @param int $value value to set flag to
     */
    public function Set(int $value = 1) {

        $this->Set = int2b($value);
    }

    /**
     * Unset flag - set flag to false
     */
    public function Unset() {

        $this->Set = 0;
    }

    /**
     * Toggle flag - bool toggle
     */
    public function Toggle() {

        $this->Set = b2int(!$this->IsSet());
    }

    /**
     * Converts int or bool to flag
     * @param mixed $value value to set
     * @throws FlagException
     */
    public static function ToFlag($value) {

        $flag = new Flag();

        if(is_bool($value)) {

            $flag->Set(b2int($value));

        } else if(is_int($value)) {

            $flag->Set($value);
            
        } else {

            throw new FlagException("Value ($value) is in unrecognized format");
        }

        return $flag;
    }

}

class Flagword {

    private $Flags;         // Flags storage
    private $Registered;    // a quick search collection

    private $FlagwordUnitSize;  public function GetFlagLocalwordUnitSize() { return $this->FlagwordUnitSize; }
    

    /**
     * Constructor
     * @param int $size_in_bytes how many bytes can flagword store
     */
    public function __construct(int $size_in_bytes = 4) {

        $this->FlagwordUnitSize = PHP_INT_SIZE;

        $layers = ceil($size_in_bytes / PHP_INT_SIZE / 8);
        $this->Flags = array();
        for($i = 0; $i < $layers; $i++) {

            $a = array();
            for($j = 0; $j < PHP_INT_SIZE * 8; $j++) {

                $a[$j] = new Flag();
            }

            $this->Flags[] = $a;
        }

        $this->Registered = new Collection();
    }

    /**
     * Register flag by name
     * @param int $index flag index
     * @param mixed $key identifaction key
     * @param bool $update update existing value (false will trigger exception)
     * @throws Exception
     */
    public function RegisterFlag(int $index, $key = null, bool $update = false) {

        try {

            $flag = $this->ByIndex($index);
            
            if(is_null($key))
                $key = $index;
            $flag->SetKey($key);

            $this->Registered->Add($flag, $key, $update);

        } catch (Exception $exc) {

            throw $exc;
        }

    }

    /**
     * Get flag by index
     * @param int $index flag index
     * @return Flag
     * @throws Exception
     */
    public function ByIndex(int $index) {

        try {

            $row = $this->LocateFlagwordLayer($index);
            $local_index = $index % (PHP_INT_SIZE * 8);
            return $this->GetFlagLocal($local_index, $row);

        } catch (Exception $exc) {

            throw $exc;
        }

    }

    /**
     * Set flag value
     * @param int $index flag index
     * @param int $value value to set
     * @return Flag
     * @throws FlagException
     */
    public function SetFlag(int $index, int $value = 1) {

        try {

            $flag = $this->ByIndex($index);    
            $flag->Set($value);

            return $flag;

        } catch (Exception $exc) {

            throw new FlagException("Setting flag error ".$exc->getMessage());
        }

    }

    /**
     * Check if all registered flags are set
     * @return bool
     */
    public function IsAllSet() {
    
        if($this->Registered->Count() == 0)
            return false;

        foreach($this->Registered->Values() as $reg) {

            if(!$reg->IsSet())
                return false;
        }

        return true;
    }


    /**
     * Gets correct flagword from flag-array by index
     * @param int $index
     * @return Flag[]
     * @throws FlagException
     */
    private function LocateFlagwordLayer(int $index) {

        if($index < 0) {

            throw new FlagException("Index $index not found");
        }

        $layer = floor($index / PHP_INT_SIZE / 8);
        if(count($this->Flags) < ($layer + 1)) {

            throw new FlagException("Out of boudn index ($index)");
        }

        return $this->Flags[$layer];
    }

    /**
     * Get flag - on local scale (from layer)
     * @param int $local_index index on local scale
     * @param Flag[] $row local layer
     */
    private function GetFlagLocal(int $local_index, array $row) {

        try {

            return $row[$local_index];

        } catch (Exception $exc) {

            throw new FlagException("Getting flag error ".$exc->getMessage());
        }
    }
}