<?php
/**
 * Admin settings page view
 *
 * @package NhrstSmartsyncTable
 */

use Nhrst\SmartsyncTable\Admin\Tables\SettingsTable;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( empty( $data ) || is_wp_error( $data ) ) {
	echo '<div class="notice notice-error"><p>' . esc_html__( 'Unable to retrieve API data.', 'nhrst-smartsync-table' ) . '</p></div>';
	return;
}

// Extract headers and rows.
$headers = $data['data']['headers'] ?? array();
$rows    = $data['data']['rows'] ?? array();
?>

<div class="wrap">
	<div class="nhrst-smartsync-table-settings-page">
		<div class="nhrst-settings-page-navbar">
			<?php
			$table_url = add_query_arg(
				array(
					'page'     => $this->page_slug,
					'_wpnonce' => wp_create_nonce( 'nhrst-settings-nonce' ),
				),
				admin_url( 'tools.php' )
			);

			$settings_url = add_query_arg(
				array(
					'page'     => $this->page_slug,
					'tab'      => 'settings',
					'_wpnonce' => wp_create_nonce( 'nhrst-settings-nonce' ),
				),
				admin_url( 'tools.php' )
			);
			?>
			
			<a href="<?php echo esc_url( $table_url ); ?>" class="tab active">
				<?php esc_html_e( 'NHR SmartSync Table', 'nhrst-smartsync-table' ); ?>
			</a>
			
		</div>
		
		<h1><?php echo esc_html( $data['title'] ?? 'API Data' ); ?></h1>

		<p class="nhrst-table-refresh-button-wrap"><button id="nhrst-table-refresh-button" class="nhrst-button nhrst-table-refresh-button"><?php esc_html_e( 'Refresh Data', 'nhrst-smartsync-table' ); ?></button></p>
		

		<form class="nhrst-settings-page-form" action="" method="post">
			<?php
			$table = new SettingsTable( $data );
			$table->prepare_items();

			$table->display();
			?>
		</form>
	</div>
</div>