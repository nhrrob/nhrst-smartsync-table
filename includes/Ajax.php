<?php
/**
 * Ajax handler class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * Ajax handler class
 */
class Ajax extends App {

	/**
	 * Class constructor
	 */
	public function __construct() { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct();
	}

	/**
	 * Initialize AJAX functionality
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'wp_ajax_nhrst_get_table_data', array( $this, 'handle_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_nhrst_get_table_data', array( $this, 'handle_ajax_request' ) );

		add_action( 'wp_ajax_nhrst_refresh_api_data', array( $this, 'refresh_api_data' ) );
	}

	/**
	 * Handle AJAX request for table data
	 *
	 * @return void
	 */
	public function handle_ajax_request() {
		check_ajax_referer( 'nhrst-common-nonce', 'nonce' );

		$api_obj = new Api();
		$data    = $api_obj->fetch_table_data();

		if ( is_wp_error( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error fetching data', 'nhrst-smartsync-table' ) ) );
		}

		wp_send_json_success( $data );
	}

	/**
	 * Refresh API data by clearing cache
	 *
	 * @return void
	 */
	public function refresh_api_data() {
		check_ajax_referer( 'nhrst-common-nonce', 'nonce' );

		delete_transient( $this->table_cache_key );

		wp_send_json_success( array( 'message' => __( 'API data refreshed', 'nhrst-smartsync-table' ) ) );
	}
}
