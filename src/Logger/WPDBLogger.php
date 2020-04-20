<?php

namespace Ayesh\WP_ErrorLog\Logger;

use Ayesh\WP_ErrorLog\LogEntry;
use wpdb;

class WPDBLogger implements LoggerInterface {

    /**
     * @var wpdb
     */
    private $database;

    private $write_queue = [];

    public function __construct(wpdb $database) {
        $this->database;
    }

    public function commit(): void {
        if (!$this->write_queue) {
            return;
        }

        foreach ($this->write_queue as $entry) {
            // todo: write in a multi-insert query.
            $this->database->insert('error_log', [
                'timestamp' => $entry->timestamp,
                'severity' => $entry->severity,
                'url' => substr($entry->url, -32000),
                'referrer' => substr($entry->url, -32000),
                'hostname' => substr($entry->url, -128),
                'error_type' => substr($entry->url, -128),
                'error_message' => $entry->error_message,
                'error_vars' => $entry !== null
                    ? json_encode($entry->error_vars)
                    : null
            ]);
        }
    }

    public function log(LogEntry $entry): void {
        $this->write_queue[] = $entry;
    }
}
