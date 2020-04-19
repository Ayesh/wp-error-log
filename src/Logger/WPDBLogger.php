<?php

namespace Ayesh\WP_ErrorLog\Logger;

use Ayesh\WP_ErrorLog\LogEntry;
use wpdb;

class WPDBLogger implements LoggerInterface {

    private $database;

    public function __construct(wpdb $database) {
        $this->database;
    }

    public function commit(): void {
        // TODO: Implement commit() method.
    }

    public function log(LogEntry $entry): void {
        // TODO: Implement log() method.
    }
}
