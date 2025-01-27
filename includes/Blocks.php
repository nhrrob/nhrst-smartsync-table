<?php

namespace Nhrst\SmartsyncTable;

/**
 * The admin class
 */
class Blocks extends App {

    /**
     * Register blocks
     *
     * @return void
     */
    public function init() {
        add_action( 'init', [ $this, 'register_blocks' ] );
    }

    /**
     * Register the blocks
     *
     * @return void
     */
    public function register_blocks() {
        register_block_type( NHRST_INCLUDES_PATH . '/blocks/table-block/build' );
    }
}
