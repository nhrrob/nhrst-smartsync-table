<?php
/**
 * Settings table class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable\Admin\Tables;

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table Class
 */
class SettingsTable extends \WP_List_Table {

	protected $headers;
	protected $data;

	public function __construct( $data = array() ) {
		parent::__construct(
			array(
				'singular' => __( 'Record', 'nhrst-smartsync-table' ),
				'plural'   => __( 'Records', 'nhrst-smartsync-table' ),
				'ajax'     => false,
			)
		);

		$this->headers = $data['data']['headers'] ?? array();
		$this->data    = $data['data']['rows'] ?? array();
	}

	/**
	 * Verify nonce for table actions
	 *
	 * @return bool
	 */
	private function verify_nonce() {
		// Skip nonce verification for initial page load.
		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'GET' === $_SERVER['REQUEST_METHOD'] && empty( $_GET['action'] ) ) {
			return true;
		}

		$nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : '';
		return wp_verify_nonce( $nonce, 'nhrst-settings-nonce' );
	}

	/**
	 * Allows you to sort the data by the variables set in the $_GET
	 *
	 * @param array $a First item to compare.
	 * @param array $b Second item to compare.
	 * @return int
	 */
	private function sort_data( $a, $b ) {
		// Set defaults.
		$allowed_orders = array( 'asc', 'desc' );

		$sortable_columns = $this->get_sortable_columns();
		$allowed_orderby  = array_keys( $sortable_columns );

		$orderby = 'id';
		$order   = 'asc';

		// If orderby is set, use this as the sort column.
		if ( ! empty( $_GET['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$orderby = sanitize_key( wp_unslash( $_GET['orderby'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$orderby = in_array( $orderby, $allowed_orderby, true ) ? $orderby : 'id';
		}

		// If order is set use this as the order.
		if ( ! empty( $_GET['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$order = sanitize_key( wp_unslash( $_GET['order'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			$order = in_array( $order, $allowed_orders, true ) ? $order : 'asc';
		}

		$result = strcmp( $a[ $orderby ], $b[ $orderby ] );

		if ( 'asc' === $order ) {
			return $result;
		}

		return -$result;
	}

	/**
	 * Message to show if no items found
	 *
	 * @return void
	 */
	public function no_items() {
		esc_html_e( 'No record found', 'nhrst-smartsync-table' );
	}

	/**
	 * Get the column names
	 *
	 * @return array
	 */
	public function get_columns() {
		$columns = array();

		foreach ( $this->headers as $header ) {
			$column_slug             = sanitize_key( strtolower( str_replace( ' ', '_', $header ) ) );
			$columns[ $column_slug ] = esc_html( $header );
		}

		return $columns;
	}

	/**
	 * Get sortable columns
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array();

		foreach ( $this->headers as $header ) {
			$column_slug                      = sanitize_key( strtolower( str_replace( ' ', '_', $header ) ) );
			$sortable_columns[ $column_slug ] = array( $column_slug, false );
		}

		return $sortable_columns;
	}

	/**
	 * Get hidden columns
	 *
	 * @return Array
	 */
	public function get_hidden_columns() {
		return array();
	}

	/**
	 * Default column values
	 *
	 * @param  array  $item        The item data.
	 * @param  string $column_name The column name.
	 *
	 * @return string
	 */
	protected function column_default( $item, $column_name ) {
		$column_name = sanitize_key( $column_name );

		switch ( $column_name ) {
			case 'created_at':
			case 'date':
				return isset( $item[ $column_name ] ) ? wp_date( get_option( 'date_format' ), intval( $item[ $column_name ] ) ) : '';

			default:
				return isset( $item[ $column_name ] ) ? esc_html( $item[ $column_name ] ) : '';
		}
	}

	/**
	 * Prepare the items
	 *
	 * @return void
	 */
	public function prepare_items() {
		if ( ! $this->verify_nonce() ) {
			wp_die( esc_html__( 'Security check failed', 'nhrst-smartsync-table' ) );
		}

		$columns  = $this->get_columns();
		$hidden   = $this->get_hidden_columns();
		$sortable = $this->get_sortable_columns();

		$data = array();
		foreach ( $this->data as $row ) {
			$row_values  = array_map( 'sanitize_text_field', array_values( $row ) );
			$column_keys = array_map( 'sanitize_key', array_keys( $columns ) );

			$data[] = array_combine( $column_keys, $row_values );
		}

		usort( $data, array( $this, 'sort_data' ) );

		$per_page     = 20;
		$current_page = $this->get_pagenum();
		$total_items  = is_array( $data ) ? count( $data ) : 0;
		$offset       = ( $current_page - 1 ) * $per_page;

		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => $per_page,
			)
		);

		$data = array_slice( $data, $offset, $per_page );

		$this->_column_headers = array( $columns, $hidden, $sortable );
		$this->items           = $data;
	}
}
