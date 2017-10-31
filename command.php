<?php

if (! class_exists('WP_CLI')) {
	return;
}

/**
 * Create a base databasedump and reset to this dump afterwards
 *
 * @when before_wp_load
 */
class ResetDB
{
	/**
	 * Create a DB-Dump in wp-content/ct-dump/base-db-dump.sql
	 *
	 */
	public function createDump()
	{
		$config = $this->getconfig();
		if (!file_exists($config->path)) {
			exec('mkdir ' . $config->path);
		}
		WP_CLI::runcommand('db export ' . $config->path . $config->file);
		WP_CLI::success('Created Database-Dump in ' . $config->path . $config->file);
	}

	/**
	 * Resets the installation
	 *
	 */
	public function resetInstallation()
	{
		$config = $this->getConfig();

		if (!file_exists($config->path . $config->file)) {
			WP_CLI::error('No basedump found. Run "wp ct create-dump" and try again');
			return 0;
		}

		WP_CLI::runcommand('db import ' . $config->path . $config->file);
		WP_CLI::success("Database successfully reseted");

		//send mail
		//reset media
		//delete cache?
		//reset plugin / theme files?
	}

	private function getConfig()
	{

		$tmp = WP_CLI::get_config();
		$config = new stdClass();
		$config->path = $tmp['path'] . 'wp-content/ct-dump/';
		$config->file = 'base-db-dump.sql';
		return $config;
	}
}

$resetCommand = new ResetDB();

WP_CLI::add_command('ct create-dump', [$resetCommand, 'createDump']);
WP_CLI::add_command('ct reset-database', [$resetCommand, 'resetInstallation']);
