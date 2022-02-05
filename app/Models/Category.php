<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $table;
    protected $table_meta;
    protected $table_category;
    protected $table_relative;

    function __construct(array $attributes = [])
    {
        parent::__construct([]);
        $this->table = isset($attributes['table']) ? $attributes['table'] : 'casinos';
        $this->table_meta = isset($attributes['table_meta']) ? $attributes['table_meta'] : 'casino_meta';
        $this->table_category = isset($attributes['table_category']) ? $attributes['table_category'] : 'casino_category';
        $this->table_relative = isset($attributes['table_relative']) ? $attributes['table_relative'] : 'casino_category_relative';
    }

    const LIMIT = 8;
    const OFFSET = 0;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;

    public function getPublicPosts($settings = []) {
        $posts = DB::table($this->table_category)
            ->where('status','public')
            ->select( '*')
            ->get();
        return $posts;
    }
    public function getPublicPostByUrl($url) {
        $post = DB::table($this->table_category)
            ->where('permalink', $url)
            ->where('status','public')
            ->select( '*')
            ->get();
        return $post;
    }
    public function getPosts($settings = []) {
        $limit     = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset    = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by  = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang      = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $posts = DB::table($this->table_category)
            ->where('lang', $lang)
            ->select( '*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function _getPublicPostById($id) {
        /*
        $posts = DB::table(self::TABLE)
            ->where('id', $id)
            ->where('status', 'public')
            ->select( '*')
            ->get();
        return $posts;
        */
    }
    public function getPostById($id) {
        $posts = DB::table($this->table_category)
            ->where('id', $id)
            ->select( '*')
            ->get();
        return $posts;
    }
    public function updateById($id, $data) {
        DB::table($this->table_category)
            ->where('id', $id)
            ->update($data);
    }
    public function _getPostByUrl($url) {
        /*
        $post = DB::table(self::TABLE)
            ->where('permalink', $url)
            ->select( '*')
            ->get();
        return $post;
        */
    }
    public function getTotalCountByLang($lang) {
        return  DB::table($this->table_category)
            ->where('lang', $lang)
            ->count();
    }
    public function getAllPostsByLang($lang) {
        return  DB::table($this->table_category)
            ->where('lang', $lang)->get();
    }
    public function _getPostByLangTitle($lang, $title) {
        /*
        return  DB::table(self::TABLE)
            ->where('lang', $lang)
            ->where('title', $title)
            ->get();
        */
    }
}