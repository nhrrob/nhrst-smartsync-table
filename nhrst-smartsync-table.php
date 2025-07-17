<?php
/**
 * Plugin Name: NHR SmartSync Table
 * Plugin URI: https://profiles.wordpress.org/nhrrob/nhrst-smartsync-table/
 * Description: A WordPress plugin to fetch data from a remote API, display it via a custom block, and manage it in the admin area.
 * Author: Nazmul Hasan Robin
 * Author URI: https://profiles.wordpress.org/nhrrob/
 * Version: 1.0.0
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * Text Domain: nhrst-smartsync-table
 * Domain Path: /languages
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package NhrstSmartsyncTable
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * The main plugin class
 */
final class Nhrst_Smartsync_Table {

	use Nhrst\SmartsyncTable\Traits\GlobalTrait;

	/**
	 * Plugin version
	 *
	 * @var string
	 */
	const NHRST_VERSION = '1.0.0';

	/**
	 * Class construcotr
	 */
	private function __construct() {
		$this->define_constants();

		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		add_action( 'plugins_loaded', array( $this, 'init_plugin' ) );

		add_action( 'init', array( $this, 'load_textdomain' ) );
	}

	/**
	 * Initialize a singleton instance
	 *
	 * @return \Nhrst_Smartsync_Table
	 */
	public static function init() {
		static $instance = false;

		if ( ! $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Define the required plugin constants
	 *
	 * @return void
	 */
	public function define_constants() {
		define( 'NHRST_VERSION', self::NHRST_VERSION );
		define( 'NHRST_FILE', __FILE__ );
		define( 'NHRST_PATH', __DIR__ );
		define( 'NHRST_URL', plugins_url( '', NHRST_FILE ) );
		define( 'NHRST_ASSETS', NHRST_URL . '/assets' );
		define( 'NHRST_PLUGIN_DIR', plugin_dir_path( NHRST_FILE ) );
		define( 'NHRST_INCLUDES_PATH', NHRST_PATH . '/includes' );
		define( 'NHRST_VIEWS_PATH', NHRST_INCLUDES_PATH . '/views' );
	}

	/**
	 * Initialize the plugin
	 *
	 * @return void
	 */
	public function init_plugin() {
		new Nhrst\SmartsyncTable\Assets();

		$ajax_obj     = new Nhrst\SmartsyncTable\Ajax();
		$api_obj      = new Nhrst\SmartsyncTable\Api();
		$cli_obj      = new Nhrst\SmartsyncTable\Cli();
		$blocks_obj   = new Nhrst\SmartsyncTable\Blocks();
		$frontend_obj = new Nhrst\SmartsyncTable\Frontend();

		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$ajax_obj->init();
		}

		if ( is_admin() ) {
			new Nhrst\SmartsyncTable\Admin();
		} else {
			$frontend_obj->init();
		}

		$api_obj->init();
		$cli_obj->init();
		$blocks_obj->init();
	}

	/**
	 * Do stuff upon plugin activation
	 *
	 * @return void
	 */
	public function activate() {
		$installer = new Nhrst\SmartsyncTable\Installer();
		$installer->run();
	}

	/**
	 * Load textdomain
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'nhrst-smartsync-table', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

// Call the plugin.
Nhrst_Smartsync_Table::init();

// Dispatch actions.
Nhrst\SmartsyncTable\Admin::dispatch_actions();
