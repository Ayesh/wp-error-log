<?php

namespace Ayesh\WP_ErrorLog\Install;

class Install {
    private $database;
    private const SCHEMA = <<<SCEMA
CREATE TABLE 
%tablename
	(
		`eid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Entry ID, an auto-incrementing ID assigned to the entry',
		`timestamp` INT UNSIGNED NOT NULL COMMENT 'UNIX timestamp when the event was recorded.',
		`severity` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Severity level of the event; lower the value, the more severe it is',
		`url` TEXT NULL DEFAULT NULL COMMENT 'URL on which the event occurred.',
		`referrer` TEXT NULL DEFAULT NULL COMMENT 'Referrer URL of the event.',
		`hostname` VARCHAR(128) NULL DEFAULT NULL COMMENT 'IPv4/IPv6 address of the client',
		`error_type` VARCHAR(128) NULL DEFAULT NULL COMMENT 'Type of the error message',
		`error_message` TEXT NULL DEFAULT NULL COMMENT 'Detailed error message contents, with placeholders',
		`error_vars` TEXT NULL DEFAULT NULL COMMENT 'A JSON-encoded array of placeholder variables',
	PRIMARY KEY (`eid`), INDEX (`severity`), INDEX (`error_type`)
	) %charset; 
SCEMA;

    public function __construct(\wpdb $database) {
        $this->database = $database;
    }

    public function setupTable(): void {
        $vars = [
            '%tablename' => $this->database->prefix . 'error_log',
            '%charset' => $this->database->get_charset_collate(),
        ];
        $sql = strtr(self::SCHEMA, $vars);
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        \dbDelta($sql);
    }
}
