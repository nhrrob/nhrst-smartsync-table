<?php

namespace Nhrst\SmartsyncTable;

/**
 * The admin class
 */
class Blocks extends App {

    /**
     * Class constructor
     */
    public function __construct() {
        parent::__construct();
    }

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
        register_block_type( 
            NHRST_INCLUDES_PATH . '/blocks/table-block/build',
            array(
                'render_callback' => array($this, 'render_table_block'),
            )
        );
    }

    public function render_table_block($attributes) {
        $apiObj = new Api();
        $data = $apiObj->fetch_table_data();

        if (is_wp_error($data)) {
            error_log($data->get_error_message());
            wp_send_json_error(['message' => __('Error fetching data', 'nhrst-smartsync-table')]);
        }
        
        if (!is_array($data) || !isset($data['data']['rows'])) {
            return '<p>Invalid data received.</p>';
        }

        // $attributes = array_map('sanitize_text_field', $attributes);
    
        ob_start();
        ?>
        <div class="nhrst-table-block-wrapper">
            <div class="nhrst-table-block-table-wrapper">
                <h3><?php echo esc_html($data['title']); ?></h3>
                <table class="nhrst-table-block-table">
                    <thead>
                        <tr>
                            <?php foreach ($attributes['showColumns'] as $column => $visible) : ?>
                                <?php if ($visible) : ?>
                                    <th><?php echo esc_html($column); ?></th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['data']['rows'] as $row) : ?>
                            <tr>
                                <?php foreach ($attributes['showColumns'] as $column => $visible) : ?>
                                    <?php if ($visible) : ?>
                                        <td><?php echo esc_html($row[$column]); ?></td>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>    
        </div>
        
        <?php
        return ob_get_clean();
    }
}
