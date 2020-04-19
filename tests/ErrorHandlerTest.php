<?php

namespace Ayesh\WP_ErrorLog\Tests;

use Ayesh\WP_ErrorLog\ErrorHandler;
use Ayesh\WP_ErrorLog\LogEntry;
use Ayesh\WP_ErrorLog\Logger\LoggerInterface;
use PHPUnit\Framework\TestCase;
use TypeError;

class ErrorHandlerTest extends TestCase {
    public function testErrorHandlerOnlyAcceptsThrowable(): void {
        $logger = new class implements LoggerInterface {

            public function log(LogEntry $entry): void {
                // TODO: Implement log() method.
            }

            public function commit(): void {
                // TODO: Implement commit() method.
            }
        };

        $handler = new ErrorHandler($logger);
        $this->expectException(TypeError::class);
        /** @noinspection PhpParamsInspection */
        $handler->handleException(new \stdClass());
    }
}
