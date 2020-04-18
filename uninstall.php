<?php

use Ayesh\WP_ErrorLog\Install\Uninstall;

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die();
}

include_once __DIR__ . '/src/Install/Uninstall.php';

global $wpdb;
$uninstall = new Uninstall($wpdb);
$uninstall->removeTable();
