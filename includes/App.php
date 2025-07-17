<?php
/**
 * App base class file
 *
 * @package NhrstSmartsyncTable
 */

namespace Nhrst\SmartsyncTable;

use Nhrst\SmartsyncTable\Traits\GlobalTrait;

/**
 * Controller Class
 */
class App {

	use GlobalTrait;

	protected $table_api_url;
	protected $table_cache_key;
	protected $table_cache_ttl;
	protected $page_slug;

	public function __construct() {
		$this->page_slug = 'nhrst-smartsync-table';

		$this->table_api_url   = 'https://miusage.com/v1/challenge/1/';
		$this->table_cache_key = 'nhrst_table_api_data';
		$this->table_cache_ttl = HOUR_IN_SECONDS;
	}
}
