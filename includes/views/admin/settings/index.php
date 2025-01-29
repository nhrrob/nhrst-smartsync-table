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
    <div class="nhrst-smartsync-table-settings-page">
        <div class="nhrst-settings-page-navbar">
            <?php 
            $table_url = add_query_arg([
                'page' => $this->page_slug,
                '_wpnonce' => wp_create_nonce('nhrst-settings-nonce')
            ], admin_url('tools.php'));
            
            $settings_url = add_query_arg([
                'page' => $this->page_slug,
                'tab' => 'settings',
                '_wpnonce' => wp_create_nonce('nhrst-settings-nonce')
            ], admin_url('tools.php'));
            ?>
            
            <a href="<?php echo esc_url($table_url); ?>" class="tab active">
                NHR SmartSync Table
            </a>
            
        </div>
        
        <h1><?php echo esc_html($data['title'] ?? 'API Data'); ?></h1>

        <p class="nhrst-table-refresh-button-wrap"><button id="refresh-data" class="nhrst-button nhrst-table-refresh-button">Refresh Data</button></p>
        

        <form class="nhrst-settings-page-form" action="" method="post">
            <?php
            $table = new SettingsTable($data);
            $table->prepare_items();
            // $table->search_box( 'search', 'search_id' );
            $table->display();
            ?>
        </form>
    </div>
</div>