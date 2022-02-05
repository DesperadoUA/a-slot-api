<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Cash extends Model
{
    const TABLE = 'cash';
    function __construct()
    {
        parent::__construct([]);
    }
    public static function get($url){
        $posts = DB::table(self::TABLE)
                     ->where('url',  $url)
                     ->get();
        return $posts;
    }
    public static function store($url, $data){
        $data_insert = [
            'url' => $url,
            'data' => $data
        ];
        DB::table(self::TABLE)->insert($data_insert);
    }
    public static function deleteAll(){
        DB::table(self::TABLE)->truncate();
    }
}