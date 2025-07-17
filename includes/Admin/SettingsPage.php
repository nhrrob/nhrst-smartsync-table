<?php
/**
 * Settings page class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable\Admin;

use Nhrst\SmartsyncTable\Api;

/**
 * The Settings page handler class
 */
class SettingsPage extends Page {

	/**
	 * Class constructor
	 */
	public function __construct() { // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
		parent::__construct();
	}

	/**
	 * Handles the settings page
	 *
	 * @return void
	 */
	public function view() {
		$api_obj = new Api();

		$data = $api_obj->fetch_table_data(); // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable

		ob_start();
		include NHRST_VIEWS_PATH . '/admin/settings/index.php';
		$content = ob_get_clean();
		echo wp_kses( $content, $this->allowed_html() );
		// phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
	}
}
