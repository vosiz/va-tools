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
     * @param string|null $uri URI to parse (path with optional query), NULL takes it from the request
     * @throws UrlParserException
    */
    public function __construct(UrlStructure $struct, ?string $uri = NULL) {
    
        try {

            if($uri === NULL)
                $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
            $this->FullUrl = $host.$uri;

            // parts come from the path only - no host, no query
            $path   = ltrim((string)parse_url($uri, PHP_URL_PATH), '/');
            $ignore = ltrim($struct->Ignore, '/');

            $this->FullWithIgnored = $path;
            if (strlen($ignore) > 0 && strpos($path.'/', $ignore) === 0) {
                $this->FullWithIgnored  = (string)substr($path, strlen($ignore));
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

            throw UrlParserException::CreateInner($exc, $exc->getMessage());
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

            throw UrlParserException::CreateInner($exc, $exc->getMessage());
        }
        
    }

}