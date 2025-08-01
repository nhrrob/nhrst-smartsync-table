<?php

namespace Nhrst\SmartsyncTable;

/**
 * WP CLI Command Class
 */
class Cli extends App {

    /**
     * Initialize the class 
     */
    function __construct() {
        parent::__construct();
    }

    public function init() {
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            // Usage (via command line): wp nhrst-table-api refresh
            \WP_CLI::add_command( 'nhrst-table-api refresh', [$this, 'refresh_data'] );
        }
    }

    public function refresh_data() {
        delete_transient( $this->table_cache_key );
        \WP_CLI::success( __('API data cache has been cleared.', 'nhrst-smartsync-table') );
    }
}
