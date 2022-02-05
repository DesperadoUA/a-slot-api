<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Relative extends Model
{
    public static function getRelativeByPostId($table, $id) {
        $data = [];
        $arr_id = DB::table($table)
            ->where('post_id',  $id)
            ->select('relative_id')
            ->get();
        if($arr_id->isEmpty()) return $data;
        else {
            foreach ($arr_id as $item) $data[] = $item->relative_id;
            return $data;
        }
    }
    public static function getPostIdByRelative($table, $id) {
        $data = [];
        $arr_id = DB::table($table)
            ->where('relative_id',  $id)
            ->select('post_id')
            ->get();
        if($arr_id->isEmpty()) return $data;
        else {
            foreach ($arr_id as $item) $data[] = $item->post_id;
            return $data;
        }
    }
    public static function insert($table, $data) {
        if(!empty($data)) DB::table($table)->insert($data);
    }
}