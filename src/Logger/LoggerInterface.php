<?php

namespace Ayesh\WP_ErrorLog\Logger;

use Ayesh\WP_ErrorLog\LogEntry;

interface LoggerInterface {
    public function log(LogEntry $entry): void;
    public function commit(): void;
}
