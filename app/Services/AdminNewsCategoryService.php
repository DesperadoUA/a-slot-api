<?php
namespace App\Services;
use App\Models\Category;
use App\Services\BaseService;
use App\Models\Cash;
use App\Serialize\CategorySerialize;
use Illuminate\Support\Facades\DB;

class AdminNewsCategoryService extends BaseService {
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->serialize = new CategorySerialize();
        $this->configTables = [
            'table' => $this->tables['NEWS'],
            'table_meta' => $this->tables['NEWS_META'],
            'table_category' => $this->tables['NEWS_CATEGORY'],
            'table_relative' => $this->tables['NEWS_CATEGORY_RELATIVE'],
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
    public function update($data) {
        $data_save = self::dataValidateCategorySave($data['id'], $data,$this->configTables['table_category'])
                   + self::checkParentCategorySave($data, $this->configTables['table_category']);
        $category = new Category($this->configTables);
        $category->updateById($data['id'], $data_save);
        Cash::deleteAll();
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function store($data) {
        $data_save = self::dataValidateCategoryInsert($data, $this->configTables['table_category'])
                           + self::checkParentCategorySave($data, $this->configTables['table_category']);
        $this->response['insert_id'] = DB::table($this->configTables['table_category'])->insertGetId($data_save);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function delete($id) {
        DB::table($this->configTables['table_category'])->where('id', $id)->delete();
        DB::table($this->configTables['table_category'])->where('parent_id', $id)->update(['parent_id' => 0]);
        Cash::deleteAll();
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
}