<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminBettingCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['BETTING'],
            'table_meta' => $this->tables['BETTING_META'],
            'table_category' => $this->tables['BETTING_CATEGORY'],
            'table_relative' => $this->tables['BETTING_CATEGORY_RELATIVE'],
        ]);
    }
}