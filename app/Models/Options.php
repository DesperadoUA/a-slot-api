<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Options extends Model
{
    const TABLE     = 'options';
    public function getAll() {
        $post = DB::table(self::TABLE)
                    ->orderBy('id','DESC')
                    ->get();
        return $post;
    }
    public function getPostById($id) {
        $post = DB::table(self::TABLE)
            ->where( 'id', $id)
            ->select( '*')
            ->get();
        return $post;
    }
    public function updateById($id, $data) {
        DB::table(self::TABLE)
            ->where('id', $id)
            ->update($data);
    }

}