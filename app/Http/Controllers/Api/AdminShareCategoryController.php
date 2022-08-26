<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCategoryService;

class AdminShareCategoryController extends AdminCategoryController {
    public function __construct() {
        parent::__construct();
        $this->service = new AdminCategoryService([
            'table' => $this->tables['SHARES'],
            'table_meta' => $this->tables['SHARE_META'],
            'table_category' => $this->tables['SHARE_CATEGORY'],
            'table_relative' => $this->tables['SHARE_CATEGORY_RELATIVE']
        ]);
    }
}