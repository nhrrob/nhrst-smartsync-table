<?php
/**
 * Admin class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * The admin class
 */
class Admin extends App {

	/**
	 * Initialize the class
	 */
	public function __construct() {
		parent::__construct();

		new Admin\Menu();
	}

	/**
	 * Dispatch and bind actions
	 *
	 * @return void
	 */
	public static function dispatch_actions() {
	}
}
