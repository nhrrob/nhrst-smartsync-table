<?php

namespace Nhrst\SmartsyncTable\Admin;

use Nhrst\SmartsyncTable\Api;
use WP_REST_Response;

/**
 * The Menu handler class
 */
class SettingsPage extends Page
{
    /**
     * Initialize the class
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Handles the settings page
     *
     * @return void
     */
    public function view()
    {
        $apiObj = new Api();

        $data = $apiObj->fetch_table_data();

        ob_start();
        include NHRST_VIEWS_PATH . '/admin/settings/index.php';
        $content = ob_get_clean();
        echo wp_kses($content, $this->allowed_html());
    }
}
