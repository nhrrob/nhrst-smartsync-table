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

    /**
     * Shortcode handler
     *
     * @param  array $atts
     * @param  string $content
     *
     * @return string
     */
    public function render_shortcode($atts, $content = '')
    {
        wp_enqueue_script('nhrst-script');
        wp_enqueue_style('nhrst-style');
        wp_enqueue_style('nhrst-admin-style');
    }
}
