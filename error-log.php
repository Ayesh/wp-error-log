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

function error_log_register_handlers(): void {
    global $wpdb;

    include_once __DIR__ . '/src/ErrorHandler.php';
    include_once __DIR__ . '/src/Logger/LoggerInterface.php';
    include_once __DIR__ . '/src/Logger/WPDBLogger.php';

    $error_handler = new ErrorHandler(new WPDBLogger($wpdb));
    $curr_error_handler = set_error_handler([$error_handler, 'handleError']);
    $curr_exception_handler = set_exception_handler([$error_handler, 'handleException']);
    $error_handler->setPreviousErrorHandler($curr_error_handler);
    $error_handler->setPreviousExceptionHandler($curr_exception_handler);
}

error_log_register_handlers();

trigger_error('dsadsa');
