<?php
/**
 * Menu handler class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable\Admin;

use Nhrst\SmartsyncTable\App;

/**
 * The Menu handler class
 */
class Menu extends App {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		parent::__construct();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		add_filter( 'plugin_action_links', array( $this, 'plugin_actions_links' ), 10, 2 );
	}

	/**
	 * Register admin menu
	 *
	 * @return void
	 */
	public function admin_menu() {
		$parent_slug = esc_html( 'nhrst-smartsync-table' );
		$capability  = esc_html( 'manage_options' );

		$hook = add_submenu_page( 'tools.php', __( 'SmartSync Table', 'nhrst-smartsync-table' ), __( 'SmartSync Table', 'nhrst-smartsync-table' ), $capability, $parent_slug, array( $this, 'settings_page' ) );

		add_action( 'admin_head-' . $hook, array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Handles the settings page
	 *
	 * @return void
	 */
	public function settings_page() {
		$settings_page = new SettingsPage();

		ob_start();
		$settings_page->view();
		$content = ob_get_clean();

		echo wp_kses( $content, $this->allowed_html() );
	}

	/**
	 * Enqueue scripts and styles
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		wp_enqueue_style( 'nhrst-admin-style' );
		wp_enqueue_script( 'nhrst-admin-script' );

		wp_enqueue_style( 'nhrst-admin-settings-style' );
		wp_enqueue_script( 'nhrst-admin-settings-script' );
	}

	/**
	 * Add settings page link on plugins page
	 *
	 * @param array  $links Plugin action links.
	 * @param string $file  Plugin file path.
	 *
	 * @return array
	 * @since 1.0.1
	 */
	public function plugin_actions_links( $links, $file ) {
		$nhrst_plugin = plugin_basename( NHRST_FILE );

		if ( $file === $nhrst_plugin && current_user_can( 'manage_options' ) ) {
			$settings_url = add_query_arg(
				array(
					'page'     => $this->page_slug,
					'_wpnonce' => wp_create_nonce( 'nhrst-settings-nonce' ),
				),
				admin_url( 'tools.php' )
			);

			$links[] = sprintf(
				'<a href="%s">%s</a>',
				esc_url( $settings_url ),
				esc_html__( 'NHR SmartSync Table', 'nhrst-smartsync-table' )
			);
		}

		return $links;
	}
}
