<?php
/**
 * Blocks class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * The blocks class
 */
class Blocks extends App {

	/**
	 * Class constructor
	 */
	public function __construct() { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct();
	}

	/**
	 * Register blocks
	 *
	 * @return void
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_blocks' ) );
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
				'render_callback' => array( $this, 'render_table_block' ),
			)
		);
	}

	/**
	 * Render table block
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render_table_block( $attributes ) {
		$api_obj = new Api();
		$data    = $api_obj->fetch_table_data();

		if ( is_wp_error( $data ) ) {
			wp_send_json_error( array( 'message' => __( 'Error fetching data', 'nhrst-smartsync-table' ) ) );
		}

		if ( ! is_array( $data ) || ! isset( $data['data']['rows'] ) ) {
			return '<p>' . esc_html__( 'Invalid data received.', 'nhrst-smartsync-table' ) . '</p>';
		}

		// Column mapping for display names.
		$column_mapping = array(
			'id'    => __( 'ID', 'nhrst-smartsync-table' ),
			'fname' => __( 'First Name', 'nhrst-smartsync-table' ),
			'lname' => __( 'Last Name', 'nhrst-smartsync-table' ),
			'email' => __( 'Email', 'nhrst-smartsync-table' ),
			'date'  => __( 'Date', 'nhrst-smartsync-table' ),
		);

		$order_by        = $attributes['orderBy'] ?? 'id';
		$order_direction = $attributes['orderDirection'] ?? 'asc';

		if ( ! array_key_exists( $order_by, $column_mapping ) ) {
			$order_by = 'id'; // Fallback to ID if invalid column.
		}

		// Sort data.
		usort(
			$data['data']['rows'],
			function ( $a, $b ) use ( $order_by, $order_direction ) {
				if ( ! isset( $a[ $order_by ] ) || ! isset( $b[ $order_by ] ) ) {
					return 0; // Skip if data is missing.
				}

				if ( 'asc' === $order_direction ) {
					return strnatcasecmp( $a[ $order_by ], $b[ $order_by ] );
				} else {
					return strnatcasecmp( $b[ $order_by ], $a[ $order_by ] );
				}
			}
		);

		ob_start();

		$date_format = get_option( 'date_format', 'Y-m-d' );
		?>
		<div class="nhrst-table-block-wrapper">
			<div class="nhrst-table-block-table-wrapper">
				<h3><?php echo esc_html( $data['title'] ); ?></h3>
				<table class="nhrst-table-block-table">
					<thead>
						<tr>
							<?php foreach ( $attributes['showColumns'] as $column => $visible ) : ?>
								<?php if ( $visible && isset( $column_mapping[ $column ] ) ) : ?>
									<th data-column="<?php echo esc_attr( $column ); ?>" class="nhrst-sortable">
										<?php echo esc_html( $column_mapping[ $column ] ); ?>
									</th>
								<?php endif; ?>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $data['data']['rows'] as $row ) : ?>
							<tr>
								<?php foreach ( $attributes['showColumns'] as $column => $visible ) : ?>
									<?php if ( $visible ) : ?>
										<td>
											<?php
											if ( 'date' === $column && ! empty( $row[ $column ] ) ) {
												echo esc_html( date_i18n( $date_format, intval( $row[ $column ] ) ) );
											} else {
												echo esc_html( ( $row[ $column ] ?? '-' ) );
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
