<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminPokerCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['POKER'],
            'table_meta' => $this->tables['POKER_META'],
            'table_category' => $this->tables['POKER_CATEGORY'],
            'table_relative' => $this->tables['POKER_CATEGORY_RELATIVE'],
        ]);
    }
}