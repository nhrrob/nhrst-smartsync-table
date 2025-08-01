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
            wp_send_json_error(['message' => __('Error fetching data', 'nhrst-smartsync-table')]);
        }
        
        if (!is_array($data) || !isset($data['data']['rows'])) {
            return '<p>'. esc_html__( 'Invalid data received.', 'nhrst-smartsync-table' ) . '</p>';
        }

        // $attributes = array_map('sanitize_text_field', $attributes);

        // Column mapping for display names
        $column_mapping = [
            'id'    => __('ID', 'nhrst-smartsync-table'),
            'fname' => __('First Name', 'nhrst-smartsync-table'),
            'lname' => __('Last Name', 'nhrst-smartsync-table'),
            'email' => __('Email', 'nhrst-smartsync-table'),
            'date'  => __('Date', 'nhrst-smartsync-table'),
        ];
    
        $orderBy = $attributes['orderBy'] ?? 'id';
        $orderDirection = $attributes['orderDirection'] ?? 'asc';
        
        if (!array_key_exists($orderBy, $column_mapping)) {
            $orderBy = 'id'; // Fallback to ID if invalid column
        }

        // Sort data
        usort($data['data']['rows'], function ($a, $b) use ($orderBy, $orderDirection) {
            if (!isset($a[$orderBy]) || !isset($b[$orderBy])) {
                return 0; // Skip if data is missing
            }
    
            if ($orderDirection === 'asc') {
                return strnatcasecmp($a[$orderBy], $b[$orderBy]);
            } else {
                return strnatcasecmp($b[$orderBy], $a[$orderBy]);
            }
        });
    
        ob_start();

        $date_format = get_option('date_format', 'Y-m-d');
        ?>
        <div class="nhrst-table-block-wrapper">
            <div class="nhrst-table-block-table-wrapper">
                <h3><?php echo esc_html($data['title']); ?></h3>
                <table class="nhrst-table-block-table">
                    <thead>
                        <tr>
                            <?php foreach ($attributes['showColumns'] as $column => $visible) : ?>
                                <?php if ($visible && isset($column_mapping[$column]) ) : ?>
                                    <th data-column="<?php echo esc_attr($column); ?>" class="nhrst-sortable">
                                        <?php echo esc_html( $column_mapping[$column] ); ?>
                                        <!-- <?php //if ($orderBy === $column) : ?>
                                            <?php //echo $orderDirection === 'asc' ? ' ↑' : ' ↓'; ?>
                                        <?php //endif; ?> -->
                                    </th>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data['data']['rows'] as $row) : ?>
                            <tr>
                                <?php foreach ($attributes['showColumns'] as $column => $visible) : ?>
                                    <?php if ($visible) : ?>
                                        <td>
                                            <?php 
                                            if ($column === 'date' && !empty($row[$column]) ) {
                                                echo esc_html( date_i18n($date_format, intval($row[$column])) );
                                            } else {
                                                echo esc_html( ($row[$column] ?? '-') );
                                            }
                                            ?>
                                        </td>
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
