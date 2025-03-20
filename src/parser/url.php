<?php

namespace Vosiz\VaTools\Parser;

require_once(__DIR__.'/exc.php');

class UrlStructure {

    public $Ignore;
    public $Keys;
    public $UsePars;

    /** 
     * Constructor
     * @param string $ignore ignore part
     * @param array $keys key Parts (../key/..)
     * @param bool $use_pars use parameters like GET
    */
    public function __construct(string $ignore = "", array $keys = array(), bool $use_pars = true) {
        
        $this->Ignore   = $ignore;
        $this->Keys     = $keys;
        $this->UsePars  = $use_pars;

        if(strlen($ignore) > 0) {

            $this->Ignore = sprintf('%s%s', $this->Ignore, '/');
        }
    }

    /**
     * Creates instance
     * @param string $ignore Ignore part
     * @param array $keys Key Parts (../key/..)
     * @param bool $use_pars Use parameters like GET
     * @return UrlStructure
     */
    public static function Create(string $ignore = "", array $keys = [], bool $use_pars = true) {

        return new UrlStructure($ignore, $keys, $use_pars);
    }
}

class UrlParser {

    protected $FullUrl;         public function GetFull()           { return $this->FullUrl; }
    protected $FullWithIgnored; public function GetFullIgnored()    { return $this->FullWithIgnored; }
    protected $Parts;           public function GetParts()          { return $this->Parts; }
    protected $Pars;            public function GetPars()           { return $this->Pars; }

    /** 
     * Constructor
     * @param UrlStructure $struct Setup scenario
    */
    public function __construct(UrlStructure $struct) {
    
        try {

            $this->FullUrl = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

            $this->FullWithIgnored = $this->FullUrl;
            if (strlen($struct->Ignore) > 0 && strpos($this->FullUrl, $struct->Ignore) === 0) {
                $this->FullWithIgnored  = substr($this->FullUrl, strlen($struct->Ignore));
            }      

            $parts = explode('/', $this->FullWithIgnored);
            $this->Parts = array();
            foreach($parts as $index => $p) {

                if($index >= count($struct->Keys))
                    continue;

                $key = $struct->Keys[$index];
                $this->Parts[$key] = explode('?', $p)[0]; 
            }

            $this->Pars = array();
            if($struct->UsePars) {
                $this->Pars = $_GET;
            }
            
        } catch(\Exception $exc) {

            throw new UrlParserException($exc->getMessage());
        }

    }


    /**
     * Get part by key
     * @param string $key key
     * @return string desired url part
     * @throws Exception
     */
    public function GetPartByKey(string $key) {

        try {

            return setifnset($this->Parts, $key, '');

        } catch(\Exception $exc) {

            throw new UrlParserException($exc->getMessage());
        }
        
    }

}