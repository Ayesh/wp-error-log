<?php

namespace Ayesh\WP_ErrorLog\Logger;

interface LoggerInterface {
    public function addContext(string $key, string $value): void;
    public function logError(int $severity, string $errstr, ?string $errfile, ?int $errline): void;
    public function logException(\Throwable $exception): void;
    public function commit(): void;
}
