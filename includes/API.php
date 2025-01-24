<?php

namespace Nhrst\SmartsyncTable;

/**
 * API Class
 */
class Api extends App {

    /**
     * Initialize the class
     */
    function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );

        add_action( 'wp_ajax_nhrst_fetch_table_data', [ $this, 'fetch_table_data' ] );
        add_action( 'wp_ajax_nopriv_nhrst_fetch_table_data', [ $this, 'fetch_table_data' ] );
    }

    /**
     * Register the API
     *
     * @return void
     */
    public function register_api() {
        //
    }

    public function fetch_table_data() {
        $cached = get_transient( $this->table_cache_key );
        if ( $cached ) {
            return $cached;
        }

        $response = wp_remote_get( $this->table_api_url );
        if ( is_wp_error( $response ) ) {
            return [];
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );
        set_transient( $this->table_cache_key, $data, $this->table_cache_ttl );

        wp_send_json_success( $data );
    }
}
