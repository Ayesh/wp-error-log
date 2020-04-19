<?php

namespace Ayesh\WP_ErrorLog\Logger;

use Ayesh\WP_ErrorLog\LogEntry;
use wpdb;

class WPDBLogger implements LoggerInterface {

    private $database;

    public function __construct(wpdb $database) {
        $this->database;
    }

    public function addContext(string $key, string $value): void {
        // TODO: Implement addContext() method.
    }

    public function logError(int $severity, string $errstr, ?string $errfile, ?int $errline): void {
        // TODO: Implement logError() method.
    }

    public function logException(\Throwable $exception): void {
        // TODO: Implement logException() method.
    }

    public function commit(): void {
        // TODO: Implement commit() method.
    }

    public function log(LogEntry $entry): void {
        // TODO: Implement log() method.
    }
}
