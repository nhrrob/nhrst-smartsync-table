<?php

namespace Nhrst\SmartsyncTable;

/**
 * Assets handler class
 */
class Assets {

    protected $asset_file;

    /**
     * Class constructor
     */
    function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
        add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ] );
        // add_action( 'enqueue_block_editor_assets', [ $this, 'register_assets' ] ); // editor only
        // add_action( 'enqueue_block_assets', [ $this, 'register_assets' ] ); // front and editor
    }

    /**
     * All available scripts
     *
     * @return array
     */
    public function get_scripts() {
        return [
            'nhrst-script' => [
                'src'     => NHRST_ASSETS . '/js/frontend.js',
                'version' => filemtime( NHRST_PATH . '/assets/js/frontend.js' ),
                'deps'    => [ 'jquery' ]
            ],
            'nhrst-admin-script' => [
                'src'     => NHRST_ASSETS . '/js/admin.js',
                'version' => filemtime( NHRST_PATH . '/assets/js/admin.js' ),
                'deps'    => [ 'jquery', 'wp-util' ]
            ],
        ];
    }

    /**
     * All available styles
     *
     * @return array
     */
    public function get_styles() {
        return [
            'nhrst-style' => [
                'src'     => NHRST_ASSETS . '/css/frontend.css',
                'version' => filemtime( NHRST_PATH . '/assets/css/frontend.css' )
            ],
            'nhrst-admin-style' => [
                'src'     => NHRST_ASSETS . '/css/admin.out.css',
                'version' => filemtime( NHRST_PATH . '/assets/css/admin.out.css' )
            ],
        ];
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

        wp_localize_script( 'nhrst-admin-script', 'nhrstSmartsyncTable', [
            'nonce' => wp_create_nonce( 'nhrst-admin-nonce' ),
            'confirm' => __( 'Are you sure?', 'nhrst-smartsync-table' ),
            'error' => __( 'Something went wrong', 'nhrst-smartsync-table' ),
            'apiUrl' => esc_url_raw(rest_url('nhrst-smartsync-table/v1/settings')),
        ] );
    }
}
