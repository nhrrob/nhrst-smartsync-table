<?php
/**
 * Assets handler class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * Assets handler class
 */
class Assets {

	/**
	 * Asset file data
	 *
	 * @var array
	 */
	protected $asset_file;

	/**
	 * Class constructor
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
	}

	/**
	 * All available scripts
	 *
	 * @return array
	 */
	public function get_scripts() {
		return array(
			'nhrst-script'        => array(
				'src'     => NHRST_ASSETS . '/js/frontend.js',
				'version' => filemtime( NHRST_PATH . '/assets/js/frontend.js' ),
				'deps'    => array( 'jquery' ),
			),
			'nhrst-admin-script'  => array(
				'src'     => NHRST_ASSETS . '/js/admin.js',
				'version' => filemtime( NHRST_PATH . '/assets/js/admin.js' ),
				'deps'    => array( 'jquery', 'wp-util' ),
			),
			'nhrst-common-script' => array(
				'src'     => NHRST_ASSETS . '/js/common.js',
				'version' => filemtime( NHRST_PATH . '/assets/js/common.js' ),
				'deps'    => array( 'jquery', 'wp-util' ),
			),
		);
	}

	/**
	 * All available styles
	 *
	 * @return array
	 */
	public function get_styles() {
		return array(
			'nhrst-style'       => array(
				'src'     => NHRST_ASSETS . '/css/frontend.css',
				'version' => filemtime( NHRST_PATH . '/assets/css/frontend.css' ),
			),
			'nhrst-admin-style' => array(
				'src'     => NHRST_ASSETS . '/css/admin.out.css',
				'version' => filemtime( NHRST_PATH . '/assets/css/admin.out.css' ),
			),
		);
	}

	/**
	 * Register scripts and styles
	 *
	 * @return void
	 */
	public function register_assets() {
		$scripts = $this->get_scripts();
		$styles  = $this->get_styles();

		foreach ( $scripts as $handle => $script ) {
			$deps = isset( $script['deps'] ) ? $script['deps'] : false;

			wp_register_script( $handle, $script['src'], $deps, $script['version'], true );
		}

		foreach ( $styles as $handle => $style ) {
			$deps = isset( $style['deps'] ) ? $style['deps'] : false;

			wp_register_style( $handle, $style['src'], $deps, $style['version'] );
		}

		wp_localize_script(
			'nhrst-admin-script',
			'nhrstSmartsyncTableObj',
			array(
				'nonce'   => wp_create_nonce( 'nhrst-admin-nonce' ),
				'confirm' => __( 'Are you sure?', 'nhrst-smartsync-table' ),
				'error'   => __( 'Something went wrong', 'nhrst-smartsync-table' ),
				'apiUrl'  => esc_url_raw( rest_url( 'nhrst-smartsync-table/v1/settings' ) ),
			)
		);

		wp_enqueue_script( 'nhrst-common-script' );

		wp_localize_script(
			'nhrst-common-script',
			'nhrstSmartSyncTableCommonObj',
			array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'nonce'       => wp_create_nonce( 'nhrst-common-nonce' ),
				'date_format' => get_option( 'date_format', 'Y-m-d' ),
			)
		);
	}
}
