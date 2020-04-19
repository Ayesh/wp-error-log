<?php

namespace Ayesh\WP_ErrorLog;

class LogEntry {
    public $eid;
    public $timestamp;
    public $severity;
    public $uid;
    public $url;
    public $referrer;
    public $hostname;
    public $error_type;
    public $error_message;
    public $error_vars;

    private static $error_types = [
        \E_ERROR => 'Error',
        \E_WARNING => 'Warning',
        \E_PARSE => 'Parse error',
        \E_NOTICE => 'Notice',
        \E_CORE_ERROR => 'Core error',
        \E_CORE_WARNING => 'Core warning',
        \E_COMPILE_ERROR => 'Compile error',
        \E_COMPILE_WARNING => 'Compile warning',
        \E_USER_ERROR => 'User error',
        \E_USER_WARNING => 'User warning',
        \E_USER_NOTICE => 'User notice',
        \E_STRICT => 'Strict warning',
        \E_RECOVERABLE_ERROR => 'Recoverable fatal error',
        \E_DEPRECATED => 'Deprecated function',
        \E_USER_DEPRECATED => 'User deprecated function'
    ];

    public function unpack(): void {
        if (is_string($this->error_vars)) {
            $this->error_vars = json_decode($this->error_vars, true);
        }
    }

    public function pack(): void {
        if (\is_string($this->error_vars)) {
            $this->error_vars = json_encode($this->error_vars);
        }
    }
}
