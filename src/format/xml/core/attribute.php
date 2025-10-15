<?php

namespace VaTools\Format\Xml;

use \Vosiz\Utils\Collections\Dictionary;

class XmlAttribute { 

    private $Name;      public function GetName()   { return $this->Name;   }
    private $Values;    public function GetValues() { return $this->Values; }


    /**
     * Prints xml attributes
     * @param Dictionary $atts atributte names and values
     * @return string 
     */
    public static function PrintArray(Dictionary $atts) {

        $prints = [];
        foreach($atts as $a) {

            $vals = $a->GetValues()->AsArray();
            if(self::IsVoid($vals)) {

                $prints[] = sprintf('%s', $a->GetName());

            } else {

                $prints[] = sprintf('%s="%s"', $a->GetName(), implode(' ', $vals));
            }
            
        }

        return implode(' ', $prints);
    }


    /**
     * Is voided attribute (like "hidden" or "readonly")
     * @param array
     * @return bool 
     */
    private static function IsVoid(array $vals) {

        if(!empty($vals)) {

            // gut inaf?
            if(current($vals) === null)
                return true;
        }
    }


    /**
     * Constructor
     * @param string $name Attribute name
     * @param array $values Attributte values
     */
    public function __construct(string $name, array $values = []) {

        $this->Name = $name;
        $this->Values = new Dictionary($values);
    }

}
