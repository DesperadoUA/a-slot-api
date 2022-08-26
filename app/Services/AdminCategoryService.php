<?php
namespace App\Services;
use App\Models\Category;
use App\Services\BaseService;
use App\Models\Cash;
use App\Serialize\CategorySerialize;
use Illuminate\Support\Facades\DB;
use App\Validate;

class AdminCategoryService extends BaseService {
    function __construct($configTables) {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->serialize = new CategorySerialize();
        $this->configTables = $configTables;
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
        $data_save = self::dataValidateCategorySave($data['id'], $data, $this->configTables['table_category'])
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
    protected static function dataValidateCategorySave($id, $data, $main_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        $newData['content'] = empty($data['content']) ? json_encode([]) : json_encode($data['content']);

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['permalink'], $main_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['title'], $main_table);
        }
        else {
            $newData['permalink'] = self::permalinkCategoryUpdate($id, $data['title'], $main_table);
        }
        if(isset($data['faq'])) {
            $newData['faq'] = json_encode($data['faq']);
        }
        else {
            $newData['faq'] = json_encode([]);
        }

        return $newData;
    }
    protected static function permalinkCategoryUpdate($id, $permalink, $main_table) {
        $candidate = DB::table($main_table)
                         ->where('permalink', $permalink)
                         ->get();
        if($candidate->isEmpty()) {
            return str_slug($permalink);
        }
        else {
            if($candidate[0]->id === $id) return $permalink;
            else {
                $counter = 0;
                do {
                    $counter++;
                    $new_permalink = $permalink.'-'.$counter;
                    $new_candidate = DB::table($main_table)
                                         ->where('permalink', $permalink)
                                         ->get();
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
    protected static function checkParentCategorySave($data, $main_table) {
        $newData['parent_id'] = 0;
        if(isset($data['parent_id'])) {
            if(!empty($data['parent_id'])){
                $current_post = DB::table($main_table)->where('id', $data['id'])->get();
                if(!$current_post->isEmpty()) {
                    $parent_post = DB::table($main_table)
                                       ->where('lang', $current_post[0]->lang)
                                       ->where('title', $data['parent_id'][0])
                                       ->get();
                    if(!$parent_post->isEmpty()) {
                        $newData['parent_id'] = $parent_post[0]->id;
                    }
                }
            }
        }
        return $newData;
    }
    protected static function dataValidateCategoryInsert($data, $main_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        $newData['content'] = empty($data['content']) ? json_encode([]) : json_encode($data['content']);

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(isset($data['permalink'])) {
            $newData['permalink'] = self::categoryPermalinkInsert($data['permalink'], $main_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::categoryPermalinkInsert($data['title'], $main_table);
        }
        else {
            $newData['permalink'] = self::categoryPermalinkInsert($data['title'], $main_table);
        }
        if(isset($data['faq'])) {
            $newData['faq'] = json_encode($data['faq']);
        }
        else {
            $newData['faq'] = json_encode([]);
        }
        return $newData;
    }
    protected static function categoryPermalinkInsert($permalink, $main_table) {
        $permalink = str_slug($permalink);
        $candidate = DB::table($main_table)->where('permalink', $permalink)->get();
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = DB::table($main_table)->where('permalink', $new_permalink)->get();
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
        }
    }
    protected static function relativeCategory($id, $tables) {
        $data = [];
        $post = new Category($tables);
        $current_post = $post->getPostById($id);
        if($current_post->isEmpty()) return $data;
        
        $arr_title_category = [];
        $list_category = $post->getAllPostsByLang($current_post[0]->lang);
        if(!$list_category->isEmpty()) {
            foreach ($list_category as $item) $arr_title_category[] = $item->title;
        }
        $data['all_value'] = $arr_title_category;
        $parent_category = $post->getPostById($current_post[0]->parent_id);
        if($parent_category->isEmpty()) $data['current_value'] = [];
        else $data['current_value'][] = $parent_category[0]->title;
        return $data;
    }
}
