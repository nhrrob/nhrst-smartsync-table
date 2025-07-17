<?php
/**
 * Installer class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * Installer class
 */
class Installer {

	/**
	 * Run the installer
	 *
	 * @return void
	 */
	public function run() {
		$this->add_version();
		$this->create_tables();
	}

	/**
	 * Add time and version on DB
	 */
	public function add_version() {
		$installed = get_option( 'nhrst_smartsync_table_installed' );

		if ( ! $installed ) {
			update_option( 'nhrst_smartsync_table_installed', time() );
		}

		update_option( 'nhrst_smartsync_table_version', NHRST_VERSION );
	}

	/**
	 * Create necessary database tables
	 *
	 * @return void
	 */
	public function create_tables() {
	}
}
