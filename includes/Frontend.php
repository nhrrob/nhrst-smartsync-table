<?php
/**
 * Frontend handler class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

/**
 * Frontend handler class
 */
class Frontend {

	/**
	 * Initialize the class
	 */
	public function __construct() {
	}

	/**
	 * Initialize frontend functionality
	 *
	 * @return void
	 */
	public function init() {
		$shortcode_obj = new Frontend\Shortcode();
		$shortcode_obj->init();
	}
}
