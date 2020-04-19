<?php

/**
 * Plugin Name: Error Log
 * Version:     1.0
 * Description: Track and monitor WordPress errors and exceptions easily right from your WordPress site.
 * Licence:     GPLv2 or later
 * Author:      Ayesh Karunaratne
 * Author URI:  https://ayesh.me/open-source
 */

use Ayesh\WP_ErrorLog\ErrorHandler;
use Ayesh\WP_ErrorLog\Logger\WPDBLogger;

error_log_register_handlers();

function error_log_get_handler(): ErrorHandler {
    static $handler;
    if ($handler) {
        return $handler;
    }

    include_once __DIR__ . '/src/ErrorHandler.php';
    include_once __DIR__ . '/src/Logger/LoggerInterface.php';
    include_once __DIR__ . '/src/Logger/WPDBLogger.php';
    include_once __DIR__ . '/src/LogEntry.php';

    global $wpdb;
    $handler = new ErrorHandler(new WPDBLogger($wpdb));
    $handler->setContext($_SERVER);

    return $handler;
}

register_activation_hook( __FILE__, static function () {
    include_once __DIR__ . '/src/Install/Install.php';
    global $wpdb;

    $installer = new Ayesh\WP_ErrorLog\Install\Install($wpdb);
    $installer->setupTable();
});


function error_log_register_handlers(): void {
    set_error_handler(
        static function (int $type, string $errstr, ?string $errfile, ?int $errline) {
            $handler = error_log_get_handler();
            $handler->handleError($type, $errstr, $errfile, $errline);
        }
    );

    set_exception_handler(static function(\Throwable $exception) {
        $handler = error_log_get_handler();
        $handler->handleException($exception);
        throw $exception;
    });

}

trigger_error('dadsa');
