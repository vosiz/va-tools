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
            'fatal'     => Signal::Create('negative', 'peak')->GetValue(),
            'exception' => Signal::Create('negative', 'intense')->GetValue(),
            
            // Special
            'fakup'     => Signal::Create('special', 'a')->GetValue(),
        ];
        self::AddValues($vals);
    } 
    
}

class Retval extends \SmartObject {

    private $Type;
    private $Message;
    private $TypeEnum;

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