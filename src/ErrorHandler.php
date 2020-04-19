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
    private $userId = 0;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public function handleError(int $error_type, string $errstr, ?string $errfile, ?int $errline): bool {
        $entry = $this->getLogEntry();
        $entry->timestamp = time();
        $entry->severity = $error_type;
        $this->logger->log($entry);
        $entry->error_type = 'PHP Error';
        $entry->error_message = $errstr . ' [In file __file on line __line]';
        $entry->error_vars = [
            '__file' => $errfile,
            '__line' => $errline,
        ];

        $this->logger->log($entry);

        if (is_callable($this->prevErroHandler)) {
            return (bool) ($this->prevExceptionHandler)($error_type, $errstr, $errfile, $errline);
        }

        return false; // trigger standard PHP handlers if available.
    }

    public function handleException(\Throwable $ex): void {
        $entry = $this->getLogEntry();
        $entry->timestamp = time();
        $entry->severity = E_ERROR;
        $entry->error_type = get_class($ex);

        $entry->error_message = $ex->getMessage() . ' [In file __file on line __line. Error code: __code]';
        $entry->error_vars = [
            '__file' => $ex->getFile(),
            '__line' => $ex->getLine(),
            '__code' => $ex->getCode(),
        ];

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
        $context =& $this->context;
        $entry->uid = $this->userId;
        $entry->hostname = isset($context['REMOTE_ADDR']) && \is_string($context['REMOTE_ADDR'])
            ? $context['REMOTE_ADDR']
            : null;
        $entry->referrer = isset($context['HTTP_REFERER']) && \is_string($context['HTTP_REFERER'])
            ? $context['HTTP_REFERER']
            : null;

        $entry->url = $context['REQUEST_SCHEME'] . '//' . $context['HTTP_HOST'] . '/' . $context['REQUEST_URI '];

        return $entry;
    }
}
