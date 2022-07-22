<?php
namespace App\Services;
use App\Validate;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use App\Models\Relative;
use App\Models\Category;

class BaseService {
    const DEFAULT_SRC = '/img/default.jpg';
    const ARR_LANG = ['ru' => 1, 'ua' => 2];
    const DEFAULT_POST_TYPE = 'default';
    protected $tables;
    public function __construct() {
        $this->tables = config('tables');
    }
    protected static function dataCommonDecode($data) {
        $newData =  [];
        $newData['id']          = $data->id;
        $newData['title']       = htmlspecialchars_decode($data->title);
        $newData['status']      = $data->status;
        $newData['create_at']   = $data->create_at;
        $newData['update_at']   = $data->update_at;
        $newData['slug']        = $data->slug;
        $newData['content']     = $data->content;
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1']          = htmlspecialchars_decode($data->h1);
        $newData['keywords']    = htmlspecialchars_decode($data->keywords);
        $newData['meta_title']  = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc']  = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail']   = $data->thumbnail;
        $newData['post_type']   = $data->post_type;
        $newData['permalink']   = $data->permalink;
        return $newData;
    }
    protected static function dataValidateSave($data) {
        $newData =  [];
        $newData['title']       = isset($data['title']) ? Validate::textValidate($data['title']) : '';
        $newData['create_at']   = isset($data['create_at']) ? $data['create_at'] : date('Y-m-d');
        $newData['update_at']   = isset($data['update_at']) ? $data['update_at'] : date('Y-m-d');
        $newData['content']     = isset($data['content']) ? $data['content'] : '';
        $newData['description'] = isset($data['description']) ? Validate::textValidate($data['description']) : '';
        $newData['h1']          = isset($data['h1']) ? Validate::textValidate($data['h1']) : '';
        $newData['keywords']    = isset($data['keywords']) ? Validate::textValidate($data['keywords']) : '';
        $newData['meta_title']  = isset($data['meta_title']) ? Validate::textValidate($data['meta_title']) : '';
        $newData['short_desc']  = isset($data['short_desc']) ? Validate::textValidate($data['short_desc']) : '';
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

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = self::DEFAULT_SRC;
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = self::DEFAULT_SRC;
        }
        
        return $newData;
    }
    protected static function permalinkInsert($permalink, $main_table, $meta_table) {
        $permalink = str_slug($permalink);
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = $post->getByPermalink($new_permalink);
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
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
    protected static function dataValidateSavePosts($id, $data, $main_table, $meta_table) {
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

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

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
            $newData['permalink'] = self::permalinkUpdate($id, $data['permalink'], $main_table, $meta_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title'], $main_table, $meta_table);
        }
        else {
            $newData['permalink'] = self::permalinkUpdate($id, $data['title'], $main_table, $meta_table);
        }

        return $newData;
    }
    protected static function permalinkUpdate($id, $permalink, $main_table, $meta_table) {
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
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
                    $new_candidate = $post->getByPermalink($new_permalink);
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
    protected static function dataDeserialize($data, $shemas) {
        $newData = [];
        foreach ($shemas as $key => $field) {
            if($field['type'] === 'number') {
                $newData[$key] = (int)$data->{$key};
            }
            elseif($field['type'] === 'string') {
                $newData[$key] = $data->{$key};
            }
            elseif($field['type'] === 'json') {
                if(empty($data->{$key})) $newData['exchange'] = [];
                else $newData[$key] = json_decode($data->{$key}, true);
            }
        }
        return $newData;
    }
    protected static function dataValidateMetaSave($data, $shemas) {
        $newData = [];
        foreach ($shemas as $key => $field) {
            if(isset($data[$key])) {
                if($shemas[$key]['type'] === 'json'){
                    $newData[$key] = json_encode($data[$key]);
                }
                elseif($shemas[$key]['type'] === 'number'){
                    $newData[$key] = (int)$data[$key];
                }
                elseif($shemas[$key]['type'] === 'string') {
                    $newData[$key] = $data[$key];
                }
            }
            else {
                if($shemas[$key]['type'] === 'json'){
                    $newData[$key] = json_encode($shemas[$key]['default']);
                } else {
                    $newData[$key] = $shemas[$key]['default'];
                }
            }
        }
        return $newData;
    }
    protected static function dataCommonValidateInsert($data, $main_table, $meta_table) {
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

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

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

        if(!isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkInsert($data['title'], $main_table, $meta_table);
        }

        if(isset($data['lang'])) {
            if(isset(self::ARR_LANG[$data['lang']])) {
                $newData['lang'] = self::ARR_LANG[$data['lang']];
            } else {
                $newData['lang'] = self::ARR_LANG['ru'];
            }
        }

        if(isset($data['post_type'])) {
            $newData['post_type'] = $data['post_type'];
        } else {
            $newData['post_type'] = self::DEFAULT_POST_TYPE;
        }
        return $newData;
    }
    protected static function dataFrontCommonDecode($data) {
        $newData = [];
        $newData['id'] = $data->id;
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['status'] = $data->status;
        $newData['create_at'] = $data->create_at;
        $newData['update_at'] = $data->update_at;
        $newData['slug'] = $data->slug;
        $str = str_replace('<pre', '<div', $data->content);
        $str = str_replace('</pre', '</div', $str);
        $str = str_replace('&nbsp;', '', $str);
        $str = str_replace('<p><br></p>', '', $str);
        $str = str_replace('<p></p>', '', $str);
        $newData['content'] = htmlspecialchars_decode($str);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail'] = $data->thumbnail;
        $newData['permalink'] = $data->permalink;
        $newData['post_type'] = $data->post_type;
        return $newData;
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

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

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
}
