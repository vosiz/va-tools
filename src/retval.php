<?php

namespace Vosiz\VaTools;

use Vosiz\Enums\Enum as Enum;

require_once(__DIR__.'/signal.php');

class RetvalType extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [

            // Undefined
            'unknown'   => Signal::Create('unknown', 'unknown')->GetValue(),

            // Success
            'success'   => Signal::Create('possitive', 'moderate')->GetValue(),
            
            // Neutral
            'notice'    => Signal::Create('neutral', 'faint')->GetValue(),
            'info'      => Signal::Create('neutral', 'moderate')->GetValue(),
            
            // Fail
            'warning'   => Signal::Create('negative', 'mild')->GetValue(),
            'fail'      => Signal::Create('negative', 'moderate')->GetValue(),
            'error'     => Signal::Create('negative', 'strong')->GetValue(),
            'fatal'     => Signal::Create('negative', 'peak')->GetValue(),
            'exception' => Signal::Create('negative', 'intense')->GetValue(),
            
            // Special
            'debug'     => Signal::Create('special', 'd')->GetValue(),
            'fakup'     => Signal::Create('special', 'f')->GetValue(),
        ];
        self::AddValues($vals);
    } 
    
}

class Retval extends \SmartObject {

    private $Type;
    private $Message;
    private $TypeEnum;

    // Specific literal instances
    public static function Success(string $fmt, ...$args)   { return self::Create('success', $fmt, ...$args);   }
    public static function Notice(string $fmt, ...$args)    { return self::Create('notice', $fmt, ...$args);    }
    public static function Info(string $fmt, ...$args)      { return self::Create('info', $fmt, ...$args);      }
    public static function Warning(string $fmt, ...$args)   { return self::Create('warning', $fmt, ...$args);   }
    public static function Fail(string $fmt, ...$args)      { return self::Create('fail', $fmt, ...$args);      }
    public static function Error(string $fmt, ...$args)     { return self::Create('error', $fmt, ...$args);     }
    public static function Gatal(string $fmt, ...$args)     { return self::Create('fatal', $fmt, ...$args);     }
    public static function Exception(string $fmt, ...$args) { return self::Create('exception', $fmt, ...$args); }
    public static function Debug(string $fmt, ...$args)     { return self::Create('debug', $fmt, ...$args);     }
    public static function Fakup(string $fmt, ...$args)     { return self::Create('fakup', $fmt, ...$args);     }

    /**
     * Creates instance
     * @param string $type retvaltype
     * @return Retval
     */
    public static function Create(string $type, string $fmt, ...$args) {

        return new Retval(RetvalType::GetEnum($type), sprintf($fmt, ...$args));
    }

    /**
     * Locked constructor
     */
    protected function __construct(RetvalType $type, string $msg) {

        parent::__construct();
        $this->Type = \ucfirst($type->GetKey());
        $this->Message = $msg;
        $this->TypeEnum = $type;
    }

    /**
     * To string override
     */
    public function __toString() {

        return sprintf("[Retval.%s]: %s", $this->Type, $this->Message);
    }

    /**
     * Compares types
     * @param string $type retval type
     * @return bool
     */
    public function Is(string $type) {

        return $this->TypeEnum->Compare(RetvalType::GetEnum($type));
    }


}