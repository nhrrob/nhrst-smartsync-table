<?php

namespace Nhrst\SmartsyncTable;

/**
 * Ajax handler class
 */
class Ajax extends App {

    /**
     * Class constructor
     */
    public function __construct() {
        parent::__construct();
    }

    public function init() {
        add_action( 'wp_ajax_nhrst_get_table_data', [ $this, 'handle_ajax_request' ] );
        add_action( 'wp_ajax_nopriv_nhrst_get_table_data', [ $this, 'handle_ajax_request' ] );

        add_action('wp_ajax_nhrst_refresh_api_data', [ $this, 'refresh_api_data' ]);
    }

    public function handle_ajax_request() {
        check_ajax_referer('nhrst-common-nonce', 'nonce');

        $apiObj = new Api();
        $data = $apiObj->fetch_table_data();

        if (is_wp_error($data)) {
            wp_send_json_error(['message' => __('Error fetching data', 'nhrst-smartsync-table')]);
        }
        
        wp_send_json_success( $data );
    }

    public function refresh_api_data() {
        check_ajax_referer('nhrst-common-nonce', 'nonce');

        delete_transient( $this->table_cache_key );
    
        wp_send_json_success(['message' => __( 'API data refreshed', 'nhrst-smartsync-table' )]);
    }
    
}
