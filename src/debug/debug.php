<?php

namespace Vosiz\VaTools\Debug;

require_once(__DIR__.'/dumper.php');

use \Vosiz\Utils\Collections\Collection;

class Debugger extends \Singleton {

    protected static $Dumper;
    protected static $Seen;

    /**
     * Dumps variable(s)
     * @param mixed ...$args VA
     */
    public static function Dump(...$args) {

        self::Init();

        foreach($args as $a) {

            self::$Dumper->DumpItem($a);
        }
    }

    /**
     * Add object which has been rendered
     * @param string $hash
     */
    public static function AddSeen(string $hash) {

        if($hash !== NULL)
            self::$Seen->Add($hash);
    }

    /**
     * Check if object has been seen (has to have some sort of GUID)
     * @param string $hash
     * @throws 
     */
    public static function HasSeen(string $hash) {

        if($hash !== NULL)
            return self::$Seen->Contains($hash);

        throw new \Exception("Adding seen as NULL");
    }

    /**
     * Inits dumper
     */
    private static function Init() {

        if(self::$Instance === NULL) {
            
            self::$Dumper = Dumper::GetInstance();
        }

        self::$Seen = new Collection();
    }

}


