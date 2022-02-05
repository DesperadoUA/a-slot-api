<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Users extends Model
{
    const TABLE     = 'users';
    static function checkAuth($id, $token) {
        $candidate = DB::table(self::TABLE)
            ->where('id',  $id)
            ->where('remember_token',  $token)
            ->get();
        if($candidate->isEmpty()) return false;
        else {
            self::updateToken($id);
            return true;
        }
    }
    static function login($name, $password) {
        $candidate = DB::table(self::TABLE)
            ->where('name',  $name)
            ->where('password',  $password)
            ->get();
        if($candidate->isEmpty()) return false;
        else return true;
    }
    static function createToken($name, $password) {
        $data = [
            'name'           => $name,
            'password'       => $password,
            'remember_token' => md5(Str::random(10)),
            'update_at'      => date('Y-m-d H:i:s')
        ];

        DB::table(self::TABLE)
            ->where('name', $data['name'])
            ->where('password', $data['password'])
            ->update($data);

        return DB::table(self::TABLE)
                   ->select('id', 'remember_token as session', 'role')
                   ->where('name', $data['name'])
                   ->where('password', $data['password'])
                   ->first();
    }
    static function updateToken($id) {
        $data = [
            'id' => $id,
            'update_at' => date('Y-m-d H:i:s')
        ];

        DB::table(self::TABLE)
            ->where('id', $data['id'])
            ->update($data);
    }
    static function logout($id){
        $data = [
            'id' => $id,
            'remember_token' => ''
        ];
        DB::table(self::TABLE)
            ->where('id', $data['id'])
            ->update($data);
    }
}