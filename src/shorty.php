<?php

/**
 * Shortcut for Debugger::Dump()
 */
require_once(__DIR__.'/debug/debug.php');
if(!function_exists('debug')) {

    function debug(...$args) {

        return \Vosiz\VaTools\Debug\Debugger::Dump(...$args);
    }

    function debugf(string $fmt, ...$args) {

        return \Vosiz\VaTools\Debug\Debugger::Dumpf($fmt, ...$args);
    }

}

/**
 * Shortcuts for Retval::Create()
 */
require_once(__DIR__.'/retval.php');
if(!function_exists('retval')) { 

    function retval(string $type, string $fmt, ...$args) {

        return \Vosiz\VaTools\Retval::Create($type, $fmt, ...$args);
    }

    function retval_success(string $fmt, ...$args)      { return retval('success', $fmt, ...$args);     }
    function retval_notice(string $fmt, ...$args)       { return retval('notice', $fmt, ...$args);      }
    function retval_info(string $fmt, ...$args)         { return retval('info', $fmt, ...$args);        }
    function retval_warning(string $fmt, ...$args)      { return retval('warning', $fmt, ...$args);     }
    function retval_fail(string $fmt, ...$args)         { return retval('fail', $fmt, ...$args);        }
    function retval_error(string $fmt, ...$args)        { return retval('error', $fmt, ...$args);       }
    function retval_fatal(string $fmt, ...$args)        { return retval('fatal', $fmt, ...$args);       }
    function retval_exception(string $fmt, ...$args)    { return retval('exception', $fmt, ...$args);   }
    function retval_fakup(string $fmt, ...$args)        { return retval('fakup', $fmt, ...$args);       }
}