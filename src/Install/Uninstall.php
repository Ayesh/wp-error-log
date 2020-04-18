<?php


namespace Ayesh\WP_ErrorLog\Install;


class Uninstall {
    private $database;

    public function __construct(\wpdb $database) {
        $this->database = $database;
    }

    public function removeTable(): void {
        $table_name = $this->database->prefix .'error_log';
        $this->database->query("DROP TABLE IF EXISTS {$table_name}");
    }
}
