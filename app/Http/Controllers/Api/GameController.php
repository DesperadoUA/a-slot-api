<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\GameService;

class GameController extends PostController
{
    public function __construct() {
        $this->service = new GameService();
    }
    const LIMIT_CASINO = 5;
    const LIMIT_GAMES = 5;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        /*
        $posts = new Posts(['post_type' => self::POST_TYPE]);
        $settings = [
            'offset'    => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit'     => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by'  => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $data = $posts->getPublicPosts($settings);
        if(!$data->isEmpty()) {
            $arr = [];
            foreach ($data as $item) {
                $arr[] = self::dataCommonDecode($item) + self::dataMetaDecode($item);
            }
            $response = [
                'body' => [
                    'posts' => $arr,
                    'total' =>  $posts->getTotalCountPublicByLang(self::POST_TYPE, $settings['lang'])
                ],
                'confirm' => 'ok'
            ];
        }
        */
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return response()->json($this->service->show($id));
    }
    public function category($id){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $settings = [
            'table' => $this->tables['GAME'],
            'table_meta' => $this->tables['GAME_META'],
            'table_category' => $this->tables['GAME_CATEGORY'],
            'table_relative' => $this->tables['GAME_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['GAME_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
                $response['body']['posts'] = CardBuilder::gameCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['category'] = CardBuilder::categoryCard($category->getPublicPosts(), 'games');

            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['banner'] = $data->banner;
        $newData['jackpot'] = $data->jackpot;
        $newData['risk_game'] = $data->risk_game;
        $newData['max_gain'] = $data->max_gain;
        $newData['max_bet'] = $data->max_bet;
        $newData['min_bet'] = $data->min_bet;
        $newData['iframe'] = $data->iframe;
        $newData['rating'] = (int)$data->rating;

        if(empty($data->gallery)) $newData['gallery'] = [];
        else $newData['gallery'] = json_decode($data->gallery, true);

        if(empty($data->characters)) $newData['characters'] = [];
        else $newData['characters'] = json_decode($data->characters, true);

        if(empty($data->details)) $newData['details'] = [];
        else $newData['details'] = json_decode($data->details, true);

        return $newData;
    }
}