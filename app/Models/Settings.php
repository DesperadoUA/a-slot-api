<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Settings extends Model
{
    const TABLE     = 'settings';
    const ORDER_BY  = 'DESC';
    const LANG      = 1;
    public function getPosts($settings = []) {
        $lang      = isset($settings['lang']) ? $settings['lang'] : self::LANG;
        $posts = DB::table(self::TABLE)
            ->where('lang', $lang)
            ->select( '*')
            ->orderBy('id','DESC')
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
    public function updateById($id, $data) {
        DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }
}