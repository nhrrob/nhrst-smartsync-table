<?php

namespace Nhrst\SmartsyncTable\Admin\Tables;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * List Table Class
 */
class SettingsTable extends \WP_List_Table {
    protected $headers;
    protected $data;

    public function __construct( $data = [] ) {
        parent::__construct( [
            'singular' => __('Record', 'nhrst-smartsync-table'),
            'plural'   => __('Records', 'nhrst-smartsync-table'),
            'ajax'     => false
        ] );

        $this->headers = $data['data']['headers'] ?? [];
        $this->data = $data['data']['rows'] ?? [];
    }

    /**
     * Message to show if no items found
     *
     * @return void
     */
    public function no_items() {
        _e( 'No record found', 'nhrst-smartsync-table' );
    }

    /**
     * Get the column names
     *
     * @return array
     */
    public function get_columns() {
        // return [
        //     'cb'         => '<input type="checkbox" />',
        //     'name'       => __( 'Name', 'nhrst-smartsync-table' ),
        //     'address'    => __( 'Address', 'nhrst-smartsync-table' ),
        //     'phone'      => __( 'Phone', 'nhrst-smartsync-table' ),
        //     'created_at' => __( 'Date', 'nhrst-smartsync-table' ),
        // ];

        $columns = [
            // 'cb' => '<input type="checkbox" />',
        ];

        foreach ($this->headers as $header) {
            $columns[strtolower(str_replace(' ', '_', $header))] = esc_html__( $header, 'nhrst-smartsync-table' );
        }

        return $columns;
    }

    /**
     * Get sortable columns
     *
     * @return array
     */
    // function get_sortable_columns() {
    //     $sortable_columns = [
    //         'name'       => [ 'name', true ],
    //         'created_at' => [ 'created_at', true ],
    //     ];

    //     return $sortable_columns;
    // }

    /**
     * Set the bulk actions
     *
     * @return array
     */
    public function get_bulk_actions() {
        $actions = array(
            'trash'  => __( 'Move to Trash', 'nhrst-smartsync-table' ),
        );

        return $actions;
    }

    /**
     * Default column values
     *
     * @param  object $item
     * @param  string $column_name
     *
     * @return string
     */
    protected function column_default( $item, $column_name ) {

        switch ( $column_name ) {

            case 'created_at':
                return wp_date( get_option( 'date_format' ), strtotime( $item->created_at ) );

            default:
                return isset( $item->$column_name ) ? esc_html( $item->$column_name ) : '';
        }
    }

    /**
     * Render the "name" column
     *
     * @param  object $item
     *
     * @return string
     */
    // public function column_name( $item ) {
    //     $actions = [];

    //     $actions['delete'] = sprintf( '<a href="#" class="submitdelete" data-id="%s">%s</a>', $item->id, __( 'Delete', 'nhrst-smartsync-table' ) );

    //     return sprintf(
    //         '<a href="%1$s"><strong>%2$s</strong></a> %3$s', admin_url( 'admin.php?page=nhrst-smartsync-table&action=view&id' . $item->id ), $item->name, $this->row_actions( $actions )
    //     );
    // }

    /**
     * Render the "cb" column
     *
     * @param  object $item
     *
     * @return string
     */
    // protected function column_cb( $item ) {
    //     return sprintf(
    //         '<input type="checkbox" name="address_id[]" value="%d" />', $item->id
    //     );
    // }

    /**
     * Prepare the address items
     *
     * @return void
     */
    public function prepare_items() {
        $columns   = $this->get_columns();
        $hidden   = [];
        // $sortable = $this->get_sortable_columns();
        $sortable = [];

        $this->_column_headers = [ $columns, $hidden, $sortable ];

        // $per_page     = 20;
        // $current_page = $this->get_pagenum();
        // $offset       = ( $current_page - 1 ) * $per_page;

        // $args = [
        //     'number' => $per_page,
        //     'offset' => $offset,
        // ];

        // if ( isset( $_REQUEST['orderby'] ) && isset( $_REQUEST['order'] ) ) {
        //     $args['orderby'] = $_REQUEST['orderby'];
        //     $args['order']   = $_REQUEST['order'] ;
        // }

        // $this->items = wd_ac_get_addresses( $args );

        // $this->set_pagination_args( [
        //     'total_items' => wd_ac_address_count(),
        //     'per_page'    => $per_page
        // ] );

        // $this->items = array_map(function ($row) {
        //     $row; // Use API-provided keys and values directly.
        // }, $this->data);

        $data = [];
        foreach ($this->data as $key => $row) {
            $row_values = array_values($row);
            $column_keys = array_keys($columns);

            $data[] = array_combine($column_keys, $row_values);
        }

        $this->items = $data;

        echo '<pre>';
        print_r($columns);
        print_r($this->items);
        echo '</pre>';
    }

}
