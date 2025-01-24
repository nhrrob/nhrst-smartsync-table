<?php if (!defined('ABSPATH')) exit; // Exit if accessed directly 

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
    <table class="fixed striped">
        <thead>
            <tr>
                <?php foreach ($headers as $header): ?>
                <th><?php echo esc_html($header); ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <?php foreach ($row as $key => $value): ?>
                        <td><?php echo esc_html($value); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>