<?php

namespace Ayesh\WP_ErrorLog;

use Ayesh\WP_ErrorLog\Logger\LoggerInterface;

class ErrorHandler {
    private $prevErroHandler;
    private $prevExceptionHandler;

    /**
     * @var LoggerInterface
     */
    private $logger;

    private const ERROR_TYPES = [
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

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handleError(int $severity, string $errstr, ?string $errfile, ?int $errline): bool {
        $this->logger->logError($severity, $errstr, $errfile, $errline);
        if (is_callable($this->prevErroHandler)) {
            return (bool) ($this->prevExceptionHandler)($severity, $errstr, $errfile, $errline);
        }

        return false; // trigger standard PHP handlers if available.
    }

    public function handleException(\Throwable $ex): void {
        $this->logger->logException($ex);
        if (is_callable($this->prevExceptionHandler)) {
            ($this->prevExceptionHandler)($ex);
        }
    }

    public function setPreviousErrorHandler(?callable $curr_error_handler): void {
        $this->prevErroHandler = $curr_error_handler;
    }

    public function __destruct() {
        $this->logger->commit();
    }

    public function setPreviousExceptionHandler(?callable $curr_error_handler): void {
        $this->prevExceptionHandler = $curr_error_handler;
    }
}
