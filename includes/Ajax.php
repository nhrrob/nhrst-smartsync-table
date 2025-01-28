<?php

namespace Nhrst\SmartsyncTable;

/**
 * Ajax handler class
 */
class Ajax {

    /**
     * Class constructor
     */
    function __construct() {
        //
    }

    public function init() {
        add_action( 'wp_ajax_nhrst_get_table_data', [ $this, 'handle_ajax_request' ] );
        add_action( 'wp_ajax_nopriv_nhrst_get_table_data', [ $this, 'handle_ajax_request' ] );
    }

    public function handle_ajax_request() {
        check_ajax_referer('nhrst-common-nonce', 'nonce');

        $apiObj = new Api();
        $data = $apiObj->fetch_table_data();

        if (is_wp_error($data)) {
            error_log($data->get_error_message());
            wp_send_json_error(['message' => __('Error fetching data', 'nhrst-smartsync-table')]);
        }
        
        wp_send_json_success( $data );
    }
    
}
