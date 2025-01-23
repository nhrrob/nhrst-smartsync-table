<?php

namespace Nhrst\SmartsyncTable;

use Nhrst\SmartsyncTable\Admin\SettingsPage;

/**
 * The admin class
 */
class Admin extends App {

    /**
     * Initialize the class
     */
    function __construct() {
        parent::__construct();
        
        new Admin\Menu( );
    }

    /**
     * Dispatch and bind actions
     *
     * @return void
     */
    public static function dispatch_actions( ) {
        
    }
}
