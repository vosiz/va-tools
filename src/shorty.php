<?php

/**
 * Shortcut for Debugger::Dump()
 */
require_once(__DIR__.'/debug/debug.php');
if (!function_exists('debug')) {

    function debug(...$args) {

        return \Vosiz\VaTools\Debug\Debugger::Dump(...$args);
    }

    function debugf(string $fmt, ...$args) {

        return \Vosiz\VaTools\Debug\Debugger::Dumpf($fmt, ...$args);
    }

}