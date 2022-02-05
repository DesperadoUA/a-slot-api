<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Pages extends Model
{
    function __construct(array $attributes = [])
    {
        parent::__construct([]);
    }

    const LIMIT     = 8;
    const OFFSET    = 0;
    const TABLE     = 'pages';
    const ORDER_BY  = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG      = 1;

    public function _getPublicPosts($settings = []) {
        $limit     = isset($settings['limit']) ? $settings['limit'] : self::LIMIT;
        $offset    = isset($settings['offset']) ? $settings['offset'] : self::OFFSET;
        $order_by  = isset($settings['order_by']) ? $settings['order_by'] : self::ORDER_BY;
        $order_key = isset($settings['order_key']) ? $settings['order_key'] : self::ORDER_KEY;
        $lang      = isset($settings['lang']) ? $settings['lang'] : self::LANG;

        $posts = DB::table(self::TABLE)
            ->where('status',  'public')
            ->where('lang', $lang)
            ->select( '*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getPublicPostByUrl($url) {
        $post = DB::table(self::TABLE)
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

        $posts = DB::table(self::TABLE)
            ->where('lang', $lang)
            ->select( '*')
            ->offset($offset)
            ->limit($limit)
            ->orderBy($order_key, $order_by)
            ->get();
        return $posts;
    }
    public function getPostById($id) {
        $posts = DB::table(self::TABLE)
            ->where('id', $id)
            ->select( '*')
            ->get();
        return $posts;
    }
    public function _getAll(){
        $posts = DB::table(self::TABLE)
            ->select( '*')
            ->get();
        return $posts;
    }
    public function updateById($id, $data) {
        DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }
    public function getPostByUrl($url) {
        $post = DB::table(self::TABLE)
            ->where('permalink', $url)
            ->select( '*')
            ->get();
        return $post;
    }
    public function getTotalCountByLang($lang) {
        return  DB::table(self::TABLE)
            ->where('lang', $lang)
            ->count();
    }
}