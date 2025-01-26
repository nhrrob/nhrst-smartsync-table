<?php

use Nhrst\SmartsyncTable\Admin\Tables\SettingsTable;

 if (!defined('ABSPATH')) exit; // Exit if accessed directly 

if (empty( $data ) || is_wp_error($data)) {
    echo '<div class="notice notice-error"><p>Unable to retrieve API data.</p></div>';
    return;
}

// Extract headers and rows
$headers = $data['data']['headers'] ?? [];
$rows = $data['data']['rows'] ?? [];
?>

<div class="wrap">
    <div id="nhrst-admin-settings">NHR SmartSync Table</div>
    
    <h1><?php echo esc_html($data['title'] ?? 'API Data'); ?></h1>

    <button id="refresh-data">Refresh Data</button>
    

    <form action="" method="post">
        <?php
        $table = new SettingsTable($data);
        $table->prepare_items();
        // $table->search_box( 'search', 'search_id' );
        $table->display();
        ?>
    </form>
</div>