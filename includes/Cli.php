<?php
/**
 * CLI command class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * WP CLI Command Class
 */
class Cli extends App {

	/**
	 * Class constructor
	 */
	public function __construct() { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct();
	}

	/**
	 * Initialize CLI functionality
	 *
	 * @return void
	 */
	public function init() {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			// Usage (via command line): wp nhrst-table-api refresh.
			\WP_CLI::add_command( 'nhrst-table-api refresh', array( $this, 'refresh_data' ) );
		}
	}

	/**
	 * Refresh API data cache
	 *
	 * @return void
	 */
	public function refresh_data() {
		delete_transient( $this->table_cache_key );
		\WP_CLI::success( __( 'API data cache has been cleared.', 'nhrst-smartsync-table' ) );
	}
}
