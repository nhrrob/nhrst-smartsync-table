<?php

namespace Nhrst\SmartsyncTable;

use Nhrst\SmartsyncTable\Traits\GlobalTrait;

/**
 * Controller Class
 */
class App {
    
    use GlobalTrait;
    
    protected $page_slug;
    
    public function __construct()
    {
        $this->page_slug = 'nhrst-smartsync-table';
    }
}
