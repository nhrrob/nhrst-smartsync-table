<?php

namespace Nhrst\SmartsyncTable;

/**
 * Frontend handler class
 */
class Frontend {

    /**
     * Initialize the class
     */
    function __construct() {
        
    }

    public function init() {
        $shortcodeObj = new Frontend\Shortcode();
        $shortcodeObj->init();
    }
}
