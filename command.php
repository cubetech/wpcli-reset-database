<?php

if (! class_exists('WP_CLI')) {
	return;
}

/**
 * Resets the Database in a specified interval
 *
 * @when before_wp_load
 */
$ct_reset_database = function () {
	
	$config = WP_CLI::get_config();
	$configPath = $config['path'] . 'wp-content/DB_Reset/';
	$baseDbName = 'baseDbBackup.sql';
	
	if (! file_exists($configPath)) {
		echo 'initialize DB-Reset folder in wp-content directory';
		exec('mkdir ' . $configPath);
		WP_CLI::runcommand('db export ' . $configPath . $baseDbName);
	} else {
		WP_CLI::runcommand('db import ' . $configPath . $baseDbName);
		WP_CLI::success("Database successfully reseted");
	}
};
WP_CLI::add_command('ct-reset-database', $ct_reset_database);
