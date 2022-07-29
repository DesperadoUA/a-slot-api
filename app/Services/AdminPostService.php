<?php
namespace App\Services;

use App\Serialize\PostSerialize;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use App\Models\Relative;
use App\Models\Cash;

class AdminPostService extends BaseService {
    public function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->serialize = new PostSerialize();
        $this->shemas = config('shemas.CASINO');
        $this->configTables = [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE'],
        ];
    }
    public function adminIndex($settings) {
        $posts = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) $data[] = $this->serialize->adminSerialize($item, $this->shemas);
        $this->response['body'] = $data;
        $this->response['confirm'] = 'ok';
        $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function store($data) {
        $data_save = $this->serialize->validateInsert($data, $this->configTables['table'], $this->configTables['table_meta']);
        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function delete($data) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $post->deleteById($data);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function updateCategory($id, $arr_titles, $main_table, $category_table, $relative_table) {
        DB::table($relative_table)->where('post_id', $id)->delete();
        if(!empty($arr_titles)) {
            $current_post = DB::table($main_table)->where('id', $id)->get();
            if(!$current_post->isEmpty()) {
                $arr_category = DB::table($category_table)
                    ->whereIn('title', $arr_titles)
                    ->where('lang', $current_post[0]->lang)
                    ->get();
                $data = [];
                foreach ($arr_category as $item) {
                    $data[] = [
                        'post_id' => $current_post[0]->id,
                        'relative_id' => $item->id
                    ];
                }
                Relative::insert($relative_table, $data);
            }
        }
    }
    public function updatePostPost($id, $arr_titles, $table_1, $table_2, $relative_table) {
        DB::table($relative_table)->where('post_id', $id)->delete();
        if(!empty($arr_titles)) {
            $current_post = DB::table($table_1)->where('id', $id)->get();
            if(!$current_post->isEmpty()) {
                $arr_relative_posts = DB::table($table_2)
                    ->whereIn('title', $arr_titles)
                    ->where('lang', $current_post[0]->lang)
                    ->get();
                $data = [];
                foreach ($arr_relative_posts as $item) {
                    $data[] = [
                        'post_id' => $current_post[0]->id,
                        'relative_id' => $item->id
                    ];
                }
                Relative::insert($relative_table, $data);
            }
        }
    }
    protected static function relativePostPost($id, $table_1, $table_2, $relative_table) {
        $data = [];
        $current_post = DB::table($table_1)->where('id', $id)->get();
        if($current_post->isEmpty()) {
            return $data;
        }
        else {
            $arr_title_relative = [];
            $list_relative = DB::table($table_2)->where('lang', $current_post[0]->lang)->get();
            if(!$list_relative->isEmpty()) {
                foreach ($list_relative as $item) $arr_title_relative[] = $item->title;
            }
            $data['all_value'] = $arr_title_relative;
            $arr_relative_post_id = Relative::getRelativeByPostId($relative_table, $current_post[0]->id);
            if(empty($arr_relative_post_id)) $data['current_value'] = [];
            else {
                $arr_category = DB::table($table_2)
                    ->whereIn('id', $arr_relative_post_id)
                    ->get();
                $data['current_value'] = [];
                foreach ($arr_category as $item) $data['current_value'][] = $item->title;
            }
            return $data;
        }
    }
    protected static function relativeCategoryPost($id, $main_table, $category_table, $relative_table) {
        $data = [];
        $current_post = DB::table($main_table)->where('id', $id)->get();
        if($current_post->isEmpty()) {
            return $data;
        }
        else {
            $arr_title_category = [];
            $list_category = DB::table($category_table)->where('lang', $current_post[0]->lang)->get();
            if(!$list_category->isEmpty()) {
                foreach ($list_category as $item) $arr_title_category[] = $item->title;
            }
            $data['all_value'] = $arr_title_category;
            $arr_relative_category_id = Relative::getRelativeByPostId($relative_table, $current_post[0]->id);
            if(empty($arr_relative_category_id)) $data['current_value'] = [];
            else {
                $arr_category = DB::table($category_table)
                                    ->whereIn('id', $arr_relative_category_id)
                                    ->get();
                $data['current_value'] = [];
                foreach ($arr_category as $item) $data['current_value'][] = $item->title;
            }
            return $data;
        }
    }
}