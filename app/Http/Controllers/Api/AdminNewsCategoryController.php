<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminNewsCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['NEWS'],
            'table_meta' => $this->tables['NEWS_META'],
            'table_category' => $this->tables['NEWS_CATEGORY'],
            'table_relative' => $this->tables['NEWS_CATEGORY_RELATIVE'],
        ]);
    }
}