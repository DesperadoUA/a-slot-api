<?php
namespace App\Services;
use App\Models\Category;
use App\Services\BaseService;
use App\Models\Cash;
use App\Serialize\CategorySerialize;

class AdminVendorCategoryService extends BaseService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->serialize = new CategorySerialize();
        $this->configTables = [
            'table' => $this->tables['VENDOR'],
            'table_meta' => $this->tables['VENDOR_META'],
            'table_category' => $this->tables['VENDOR_CATEGORY'],
            'table_relative' => $this->tables['VENDOR_CATEGORY_RELATIVE'],
        ];
    }
    public function adminIndex($settings) {
        $category = new Category($this->configTables);
        $arrPosts = $category->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) $data[] = $this->serialize->adminSerialize($item);
        $this->response['confirm'] = 'ok';
        $this->response['body'] = $data;
        $this->response['total'] = $category->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function adminShow($id) {
        $category = new Category($this->configTables);
        $data = $category->getPostById($id);
        if (!empty(count($data))) {
            $this->response['confirm'] = 'ok';
            $this->response['body'] = $this->serialize->adminSerialize($data[0]);
            $this->response['body']['relative_category'] = self::relativeCategory($data[0]->id,  $this->configTables);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
}