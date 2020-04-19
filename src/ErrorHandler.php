<?php

namespace Ayesh\WP_ErrorLog;

use Ayesh\WP_ErrorLog\Logger\LoggerInterface;

final class ErrorHandler {
    private $prevErroHandler;
    private $prevExceptionHandler;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var LogEntry
     */
    private $logEntry;


    /**
     * @var array
     */
    private $context;

    /**
     * @var int
     */
    private $userId;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handleError(int $severity, string $errstr, ?string $errfile, ?int $errline): bool {
        $entry = $this->getLogEntry();
        $this->logger->log($entry);
        if (is_callable($this->prevErroHandler)) {
            return (bool) ($this->prevExceptionHandler)($severity, $errstr, $errfile, $errline);
        }

        return false; // trigger standard PHP handlers if available.
    }

    public function handleException(\Throwable $ex): void {
        $entry = $this->getLogEntry();
        $this->logger->log($entry);
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

    public function setContext(array &$server_vars): void {
        $this->context = &$server_vars;
    }

    public function setUid(int $uid = 0): void {
        $this->userId = $uid;
    }

    private function getLogEntry(): LogEntry {
        if ($this->logEntry) {
            return clone $this->logEntry;
        }

        $this->logEntry = $entry = new LogEntry();
        $this->logEntry->uid = $this->userId;
        return $entry;
    }
}
