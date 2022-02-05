<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;
use DB;

class MigrateController extends Controller
{
    public function index() {
        $data = include('data.php');
        foreach ($data as $item) {
            $common_data = [
                'title' => $item['title'],
                'h1' => $item['h1'],
                'meta_title' => $item['meta_title'],
                'description' => $item['description'],
                'content' => '<pre class="ql-syntax" spellcheck="false">'.htmlspecialchars($item['content'])."</pre>",
                'lang' => $item['lang'],
                'permalink' => $item['permalink'],
                'post_type' => $item['post_type'],
                'status' => 'public',
                'slug' => 'bonus',
                'thumbnail' => 'http://127.0.0.1:8000/img/default.jpg',
                'short_desc' => '',
                'keywords' => ''
            ];
            $insert_id = DB::table('bonuses')->insertGetId( $common_data );
            $meta_data = [
                'post_id' => $insert_id,
                'close' => $item['close'],
                'ref' => json_encode($item['ref']),
                'wager' => $item['wager'],
                'number_use' => $item['number_use'],
                'value_bonus' => $item['value_bonus']
            ];
            DB::table('bonus_meta')->insert( $meta_data );
        }
        return "Good day";
    }
    public function test (){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));

        $ids = [];
        foreach ($data as $item) {
            $id = DB::table('games')
                      ->select('id')
                      ->where( 'title', $item->title )
                      ->first();
            $rating = (int)$item->rating;
            $data_update = [
                'post_id' => $id,
                'rating' => $rating
            ];
/*
            DB::table('game_meta')
                ->where('post_id', $id->id)
                ->update(['rating' => $rating]);
*/
        }
        echo "<pre>";
        var_dump($ids);
        echo "</pre>";
    }
    public function casino (){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        //echo count($data);
        foreach ($data as $item) {
            $common_data = [
                'title' => $item->title,
                'h1' => $item->h1,
                'meta_title' => $item->meta_title,
                'description' => $item->meta_description,
                'content' => '<pre class="ql-syntax" spellcheck="false">'.htmlspecialchars($item->content)."</pre>",
                'lang' => $item->lang,
                'permalink' => $item->permalink,
                'post_type' => 'casino',
                'status' => 'public',
                'slug' => 'casino',
                'thumbnail' => 'http://127.0.0.1:8000/img/default.jpg',
                'short_desc' => '',
                'keywords' => ''
            ];
            $insert_id = DB::table('casinos')->insertGetId( $common_data );
            $meta_data = [
                'post_id' => $insert_id,
                'close' => $item->close,
                'ref' => json_encode($item->ref),
                'reviews' => json_encode($item->reviews),
                'rating' => $item->rating,
                'faq' => json_encode($item->faq),
                'phone' => $item->phone,
                'min_deposit' => $item->min_deposit,
                'min_payments' => $item->min_payments,
                'email' => $item->email,
                'chat' => $item->chat,
                'year' => $item->year,
                'site' => $item->site,
                'withdrawal' => $item->withdrawal,
                'number_games' => $item->number_games,
            ];
            //DB::table('casino_meta')->insert( $meta_data );
        }
    }
    public function casinoCategory(){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        $db = [
            'main' => 'casinos',
            'support' => 'type_payments',
            'relative' => 'casino_type_payment_relative'
        ];
        //self::createRelative($db, $data);
        //return 'Good day';
    }
    public function gameCategory(){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        $db = [
            'main' => 'games',
            'support' => 'game_category',
            'relative' => 'game_category_relative'
        ];
        //self::createRelative($db, $data);
        //return 'Good day';
    }
    public function bonusCategory(){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        $db = [
            'main' => 'bonuses',
            'support' => 'casinos',
            'relative' => 'bonus_casino_relative'
        ];
        //self::createRelative($db, $data);
        //return 'Good day';
    }
    public function pokerCategory(){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        $db = [
            'main' => 'pokers',
            'support' => 'payments',
            'relative' => 'poker_payment_relative'
        ];
        //self::createRelative($db, $data);
        //return 'Good day';
    }
    public function game (){
        $path_store = $_SERVER['DOCUMENT_ROOT'].'/array.txt';
        $data = json_decode(file_get_contents($path_store));
        //echo count($data);
        foreach ($data as $item) {
            $common_data = [
                'title' => $item->title,
                'h1' => $item->h1,
                'meta_title' => $item->meta_title,
                'description' => $item->meta_description,
                'content' => '<pre class="ql-syntax" spellcheck="false">'.htmlspecialchars($item->content)."</pre>",
                'lang' => $item->lang,
                'permalink' => $item->permalink,
                'post_type' => 'game',
                'status' => 'public',
                'slug' => 'game',
                'thumbnail' => $item->thumbnail,
                'short_desc' => '',
                'keywords' => ''
            ];
            $insert_id = DB::table('games')->insertGetId( $common_data );
            $meta_data = [
                'post_id' => $insert_id,
                'banner' => $item->thumbnail,
                'jackpot' => $item->jackpot,
                'characters' => json_encode($item->characters),
                'risk_game' => $item->risk_game,
                'max_gain' => $item->max_gain,
                'max_bet' => $item->max_bet,
                'min_bet' => $item->min_bet,
                'iframe' => $item->iframe,
                'details' => json_encode($item->details),
                'gallery' => json_encode($item->gallery),

            ];
          //  DB::table('game_meta')->insert( $meta_data );
        }
    }
    protected static function createRelative($db, $data) {
        foreach ($data as $item) {
            $id = DB::table($db['main'])
                ->select('id')
                ->where('title', $item->title)
                ->first();
            if(!empty($id)) {
                foreach ($item->tax as $tax) {
                    $current_lang_id = DB::table($db['support'])
                        ->select('id')
                        ->where('title', $tax)
                        ->first();
                    if(!empty($current_lang_id)) {
                        $current_relative = [
                            'post_id' => $id->id,
                            'relative_id' => $current_lang_id->id
                        ];
                        DB::table($db['relative'])->insert($current_relative);
                    }
                }
            }
        }
    }
}