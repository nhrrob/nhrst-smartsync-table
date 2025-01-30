<?php
/**
 * Plugin Name: NHR SmartSync Table by Nazmul Hasan Robin
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
 */

use Nhrst\SmartsyncTable\App;

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
    const nhrst_version = '1.0.0';

    /**
     * Class construcotr
     */
    private function __construct() {
        $this->define_constants();

        register_activation_hook( __FILE__, [ $this, 'activate' ] );

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
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
        define( 'NHRST_VERSION', self::nhrst_version );
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

        $ajaxObj = new Nhrst\SmartsyncTable\Ajax();
        $apiObj = new Nhrst\SmartsyncTable\Api();
        $cliObj = new Nhrst\SmartsyncTable\Cli();
        $blocksObj = new Nhrst\SmartsyncTable\Blocks();
        $frontendObj = new Nhrst\SmartsyncTable\Frontend();

        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            $ajaxObj->init();
        }

        if ( is_admin() ) {
            new Nhrst\SmartsyncTable\Admin();
        } else {
            $frontendObj->init();
        }

        $apiObj->init();
        $cliObj->init();
        $blocksObj->init();
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
}

/**
 * Initializes the main plugin
 *
 * @return \Nhrst_Smartsync_Table
 */
function nhrst_smartsync_table() {
    return Nhrst_Smartsync_Table::init();
}

// Call the plugin
nhrst_smartsync_table();

// Dispatch actions
Nhrst\SmartsyncTable\Admin::dispatch_actions();