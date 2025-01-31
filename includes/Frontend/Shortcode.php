<?php

namespace Nhrst\SmartsyncTable\Frontend;

use Nhrst\SmartsyncTable\App;

/**
 * Shortcode handler class
 */
class Shortcode extends App
{

    /**
     * Initialize the class
     */
    function __construct()
    {

    }

    public function init() {
        // add_shortcode('nhrst_table_block', [$this, 'render_nhrst_table_block']);
    }
    /**
     * Shortcode handler
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_nhrst_table_block($atts, $content = '')
    {
        // wp_enqueue_script('nhrst-script');
        // wp_enqueue_style('nhrst-style');
        // wp_enqueue_style('nhrst-admin-style');
    }
}
