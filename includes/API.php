<?php

namespace Nhrst\SmartsyncTable;

/**
 * API Class
 */
class Api extends App {

    /**
     * Class constructor 
     */
    public function __construct() {
        parent::__construct();
    }

    public function init() {
        add_action( 'rest_api_init', [ $this, 'register_api' ] );
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
        
        return $data;
    }

}
