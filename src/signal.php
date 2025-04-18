<?php

namespace Vosiz\VaTools;

use Vosiz\Enums\Enum as Enum;

class Signal {

    private $Strength;  public function GetStrength()   { return $this->Strength;   }
    private $Type;      public function GetType()       { return $this->Type;   	}
    private $Value;     public function GetValue()      { return $this->Value;      }

    /**
     * Creates Signal
     * @param string $type signal type key
     * @param string $strength signal strength key
     * @return Signal
     */
    public static function Create(string $type, string $strength) {

        return new Signal(
            SignalType::GetEnum($type),
            SignalStrength::GetEnum($strength)
        );
    }

    /**
     * Locked constructor
     */
    protected function __construct(SignalType $type, SignalStrength $strength) {

        $this->Strength = $strength;
        $this->Type = $type;
        $this->Value = $strength->GetValue() + $type->GetValue();
    }

}

class SignalStrength extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [

            // undefinde
            'unknown'   => 0x00,

            // levels
            'none'      => 0x01,
            'faint'     => 0x02,
            'mild'      => 0x03,
            'moderate'  => 0x04,
            'strong'    => 0x05,
            'intense'   => 0x06,
            'peak'      => 0x07,

            // over limit
            'overload'  => 0x08,
            'critical'  => 0x09,

            // special
            'speciala'  => 0x0A,
            'specialb'  => 0x0B,
            'specialc'  => 0x0C,
            'speciald'  => 0x0D,
            'speciale'  => 0x0E,
            'specialf'  => 0x0F,
            'a'         => 0x0A,
            'b'         => 0x0B,
            'c'         => 0x0C,
            'd'         => 0x0D,
            'e'         => 0x0E,
            'f'         => 0x0F,
        ];
        self::AddValues($vals);
    }
}


class SignalType extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'unknown'   => 0x00,
            'possitive' => 0x10,
            'neutral'   => 0x20,
            'negative'  => 0x30,
            'special'   => 0x40,
        ];
        // 
        self::AddValues($vals);
    } 
    
}
