<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class BaseController extends Controller {
    const DEFAULT_POST_TYPE = 'default';
    const ARR_LANG = ['ru' => 1, 'ua' => 2];
    const SLUG = 'default';
    const OFFSET = 0;
    const LIMIT = 8;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;
    protected $tables;
    public function __construct() {
        $this->tables = config('tables');
    }
}