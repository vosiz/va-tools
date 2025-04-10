<?php

namespace Vosiz\VaTools\Debug;

use Vosiz\Enums\Enum as Enum;

class DebugDumperTypeEnum extends Enum {

    /**
     * Abstract implementation
     */
    public static function Init(): void {

        $vals = [
            'default'   => 0x00,    
            'html'      => 0x00,    // broswer
            // 'stream'    => 0x01,    // stream (str)
            //'callback'  => 0x02,    // custom callback
        ];
        self::AddValues($vals);
    } 
    
}


class DebugDumperSettings {

    private $Separator; public function GetSeparator()  { return $this->Separator;  }
    private $Type;      public function GetType()       { return $this->Type;       }
    private $Callback;  public function GetCallback()   { return $this->Callback;   }

    /**
     * Constructor
     */
    public function __construct(string $type = 'default', $callback = NULL, $sep = "&nbsp;&nbsp;&nbsp;&nbsp;") {

        try {

            $this->Type = DebugDumperTypeEnum::GetEnum($type);
            $this->Callback = $callback;
            $this->Separator = $sep;

        } catch (Exception $exc) {

            throw $exc;
        }

    }
}


class Dumper extends \Singleton {

    private $DumperSettings;
    
    /**
     * Gets hash/id form object-like type
     * @param mixed $var
     * @return string|NULL hash
     */
    public static function ToHash($var) {

        if(is_object($var)) {

            return spl_object_id($var);

        } else if(is_smarto($var)) {

            return $var->GetHash();
            
        } else {

            return NULL;
        }
    }

    /**
     * Constructor
     */
    public function __construct($render_type = NULL, $render_function = NULL) {

        if($render_type === NULL) {

            $this->DumperSettings = new DebugDumperSettings('default');

        } else {

            $this->DumperSettings = new DebugDumperSettings($render_type, $render_function);
        }

    }

    /**
     * Dumps single item
     * @param mixed $var item to dump
     * @param int $level drives separator
     */
    public function DumpItem($var, int $level = 0, $parent = NULL) {

        // recursion
        $hash = self::ToHash($var);
        if($hash !== NULL) {

            if(Debugger::HasSeen($hash)) {

                return $this->Recursion($hash);
            }

            Debugger::AddSeen($hash);
        }
        
        // check type
        if(is_array($var)) { // array

            $this->DumpArray($var, $level, $parent);

        } else if(is_collection($var)) { // collection

            $this->DumpCollection($var, $level, $parent);

        } else if(is_smarto($var)) { // smart object

            $this->DumpSmartObject($var, $level, $parent);

        } else if(is_object($var)) { // object

            $this->DumpObject($var, $level, $parent);

        } else { // scalar

            $this->DumpScalar($var, $level, $parent);
        }

    }

    /**
     * Renders backtracing
     */
    public function Backtrace($ignore_to_level = 0) {

        $backtrace = debug_backtrace();
        $this->Render("Call stack");

        foreach ($backtrace as $index => $trace) {
            if ($index <= $ignore_to_level) 
                continue; // ignore debug function
            $this->Render(sprintf("- [%s] %s(...) line %s in %s", $index - 1, $trace['function'], $trace['line'], $trace['file']));
        }
    }


    /**
     * Dumps collection
     * @param mixed $var
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpCollection($var, int $lvl, $parent) {

        $a = $var->AsArray();

        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);

        $this->Render($this->DumpFormat("Collection::%s (c=%s)", $var->GetType(), $var->Count()));
        $lvl++;
        foreach($a as $k => $v) {

            $this->DumpArrayItem($k, $v, $lvl, $var);
        }
    }

    /**
     * Dumps smart object
     * @param mixed $var
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpSmartObject($var, int $lvl, $parent) {

        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);

        $this->Render($this->DumpFormat("SmartObject: %s %s", 
            $var->ToString(),
            $this->ShortHash($var->GetHash())
        ));
        $lvl++;
        foreach(\getprops($var) as $prop) {

            $this->DumpProperty($prop, $lvl, $var);
        }
    }

    /**
     * Dumps property of class
     * @param mixed $var array[name, type, value]
     * @param int $lvl level
     * @param mixed $parent container
     * @throws Exception
     */
    private function DumpProperty($var, int $lvl, $parent) {

        try {

            $name = $var['name'];
            $type = $var['type'];
            $value = $var['value'];

            $this->Render($this->Tabing($lvl), false);

            $this->Render($this->DumpFormat("[%s] %s = ", $type, $name), false);
            $this->DumpItem($value, $lvl);

        } catch(Exception $exc) {

            throw $exc;
        }
    }

    /**
     * Dump array item
     * @param string|int $key assoc array key
     * @param mixed $value array value
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpArrayItem($key, $value, int $lvl, $parent) {

        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);
        $this->Render($this->DumpFormat("[%s] => ", $key), false);
        $this->DumpItem($value, $lvl);
    }

    /**
     * Dumps primitive/scalar
     * @param mixed $var scalar
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpScalar($var, int $lvl, $parent) {

        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);

        $this->Render($this->DumpFormat("%s (%s)", $var, typeof($var)));
    }

    /**
     * Dumps object
     * @param mixed $var
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpObject($var, int $lvl, $parent) {

        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);

        $this->Render($this->DumpFormat("Object: %s", method_exists($var, '__toString') ? $var->__toString() : typeof($var)));
        $lvl++;
        foreach(\getprops($var) as $prop) {

            $this->DumpProperty($prop, $lvl, $var);
        }

        return self::GetHash($var);
    }

    /**
     * Dumps array
     * @param array $var array
     * @param int $lvl level
     * @param mixed $parent container
     */
    private function DumpArray(array $var, int $lvl, $parent) {
        
        if($parent !== NULL)
            $this->Render($this->Tabing($lvl), false);

        $this->Render($this->DumpFormat("Array (c=%s)", count($var)));
        $lvl++;
        foreach($var as $k => $v) {

            $this->DumpArrayItem($k, $v, $lvl, $var);
        }
    }

    /**
     * Formats to string
     * @param string $fmt format
     * @param mixed ...$args VA
     * @return string
     */
    private function DumpFormat(string $fmt, ...$args) {

        return sprintf($fmt, ...$args);
    }

    /**
     * Separator rendering
     * @param int $level repeating separator
     * @return string
     */
    private function Tabing(int $level) {

        $separing = \str_repeat($this->DumperSettings->GetSeparator(), $level);
        return $this->DumpFormat("%s ", $separing);
    }

    /**
     * Renders recurection detection
     * @param string $hash
     */
    private function Recursion(string $hash) {

        $this->Render("![Recursion detect] ".$this->ShortHash($hash));
    }

    /**
     * Processes dump to output 
     * @return mixed
     */
    private function Render(string $str, $nl = true) {

        switch($this->DumperSettings->GetType()) {

            default:
                return $this->DefaultRender($str, $nl);
        }
    }

    /**
     * Default broswer based rendering (echo + </br>)
     * @param string $str what to show
     */
    private function DefaultRender(string $str, $nl) {

        echo sprintf("%s%s", $str, $nl ? "</br>" : "");
    }

    /**
     * Shorter version of hash
     * @param string $hash
     * @param string
     */
    private function ShortHash(string $hash) {

        return sprintf("#%s", substr($hash, strlen($hash) - 4, 4));
    }

}