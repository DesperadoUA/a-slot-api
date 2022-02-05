<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Posts extends Model
{
    protected $table;
    protected $table_meta;

    function __construct(array $attributes = [])
    {
        parent::__construct([]);
        $this->table = isset($attributes['table']) ? $attributes['table'] : 'casinos';
        $this->table_meta = isset($attributes['table_meta']) ? $attributes['table_meta'] : 'casino_meta';
    }

    const LIMIT = 8;
    const OFFSET = 0;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;
    const TABLE_WITH_RATING = ['game_meta', 'casino_meta', 'vendor_meta'];

    public function getPublicPosts($settings = [])
    {
        $limit = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $t1 = $this->table;
        $t2 = $this->table_meta;

        $posts = DB::table($t1)
            ->where($t1 . '.status', 'public')
            ->where($t1 . '.lang', $lang)
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getPublicPostsWithOutIds($settings){
        $limit = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang = isset($settings['lang']) ? $settings['lang'] : self::LANG;
        $exclude = isset($settings['exclude']) ?  $settings['exclude'] : [];

        $t1 = $this->table;
        $t2 = $this->table_meta;

        $posts = DB::table($t1)
            ->where($t1 . '.status', 'public')
            ->where($t1 . '.lang', $lang)
            ->whereNotIn($t1 . '.id', $exclude)
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getPublicPostByUrl($url)
    {

        $t1 = $this->table;
        $t2 = $this->table_meta;

        $post = DB::table($t1)
            ->where($t1 . '.permalink', $url)
            ->where($t1 . '.status', 'public')
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->get();
        return $post;
    }
    public function insert($common_data, $meta_data)
    {
        $insert_id = DB::table($this->table)->insertGetId($common_data);
        $meta_data['post_id'] = $insert_id;
        DB::table($this->table_meta)->insert($meta_data);
        return $insert_id;
    }
    public function getTotalCountPublicByLang($lang = self::LANG)
    {
        /*
        return DB::table(self::TABLE)
            ->where('status', 'public')
            ->where('lang', $lang)
            ->count();
        */
    }
    public function getAll($post_type)
    {
        /*
        $post = DB::table(self::TABLE)
            ->where('post_type', $post_type)
            ->orderBy('create_at', 'desc')
            ->get();
        return $post;
        */
    }
    public function getPostById($id)
    {
        $t1 = $this->table;
        $t2 = $this->table_meta;

        $post = DB::table($t1)
            ->where('id', $id)
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->get();
        return $post;
    }
    public function updateById($id, $data)
    {
        DB::table($this->table)
            ->where('id', $id)
            ->update($data);
    }
    public function getByPermalink($permalink)
    {
        $post = DB::table($this->table)
            ->where('permalink', $permalink)
            ->get();
        return $post;
    }
    public function getPosts($settings = [])
    {
        $limit = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $t1 = $this->table;
        $t2 = $this->table_meta;

        $posts = DB::table($t1)
            ->where($t1 . '.lang', $lang)
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getTotalCountByLang($lang = self::LANG)
    {
        return DB::table($this->table)
            ->where('lang', $lang)
            ->count();
    }
    public function relativeCategoryInsert($id, $arr_category_id)
    {
        /*
        if (!empty($arr_category_id)) {
            $data = [];
            foreach ($arr_category_id as $item) {
                $data[] = [
                    'post_id' => $id,
                    'category_id' => $item
                ];
            }
            DB::table(self::CATEGORY_RELATIVE_DB)
                ->insert($data);
        }
        */
    }
    public function updateMetaById($id, $data)
    {
        DB::table($this->table_meta)
            ->where('post_id', $id)
            ->update($data);
    }
    public static function searchByTitle($lang, $db, $str)
    {
        $posts = [];
        if (!empty($str)) {
            $posts = DB::table($db)
                ->where('lang', $lang)
                ->where('title', 'like', '%' . $str . '%')
                ->get();
        }
        return $posts;
    }
    public static function searchPublicByTitle($lang, $db, $str) {
        $posts = [];
        if (!empty($str)) {
            $posts = DB::table($db)
                ->where('lang', $lang)
                ->where('status', 'public')
                ->where('title', 'like', '%' . $str . '%')
                ->get();
        }
        return $posts;
    }
    public function getPublicPostsByArrId($arr)
    {
        $order_by = self::ORDER_BY;
        $order_key = in_array($this->table_meta, self::TABLE_WITH_RATING) ? 'rating' : self::ORDER_KEY;

        $t1 = $this->table;
        $t2 = $this->table_meta;

        if (empty($arr)) return [];
        $posts = DB::table($t1)
            ->where($t1 . '.status', 'public')
            ->whereIn($t1 . '.id', $arr)
            ->join($t2, $t1 . '.id', '=', $t2 . '.post_id')
            ->select($t1 . '.*', $t2 . '.*')
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function deleteById($id){
        DB::table($this->table)->where('id', $id)->delete();
    }

}