<?php

namespace Nhrst\SmartsyncTable;

/**
 * WP CLI Command Class
 */
class CLI extends App {

    /**
     * Initialize the class
     */
    function __construct() {
        $this->init();
    }

    public function init() {
        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            // \WP_CLI::add_command( 'example', [$this, 'hello'] );
            \WP_CLI::add_command( 'nhrst-table-api refresh', [$this, 'refresh_data'] );
        }
    }

    public function refresh_data() {
        delete_transient( $this->table_cache_key );
        \WP_CLI::success( 'API data cache has been cleared.' );
    }

    /**
     * Prints a greeting.
     *
     * ## OPTIONS
     *
     * <name>
     * : The name of the person to greet.
     *
     * [--type=<type>]
     * : Whether or not to greet the person with success or error.
     * ---
     * default: success
     * options:
     *   - success
     *   - error
     * ---
     *
     * ## EXAMPLES
     *
     *     wp example hello Newman
     *
     * @when after_wp_load
     */
    public function hello( $args, $assoc_args ) {
        list( $name ) = $args;

        // Print the message with type
        $type = $assoc_args['type'];
        \WP_CLI::$type( "Hello, $name!" );
    }
}
