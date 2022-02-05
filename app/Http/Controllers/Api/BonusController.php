<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Relative;

class BonusController extends PostController
{

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
    public function show($id)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

            $response['body']['casino'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            $casino_id = $arr_posts;
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $response['body']['casino'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }

            $response['body']['type_bonus'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['BONUS_TYPE_BONUS_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TYPE_BONUS'], 'table_meta' => $this->tables['TYPE_BONUS_META']]);
                $response['body']['type_bonus'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }

            $response['body']['country'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['BONUS_COUNTRY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['COUNTRY'], 'table_meta' => $this->tables['COUNTRY_META']]);
                $response['body']['country'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }

            $response['body']['bonuses'] = $casino_id;
            if(!empty($casino_id)) {
                $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CASINO_RELATIVE'], $casino_id[0]);
                if(!empty($arr_posts)) {
                    $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                    $new_arr = [];
                    foreach ($arr_posts as $item) {
                        if($item !== $data[0]->id) $new_arr[] = $item;
                    }
                    $response['body']['bonuses'] = CardBuilder::bonusCard($post->getPublicPostsByArrId($new_arr));
                }
            }

            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function category($id){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $settings = [
            'table' => $this->tables['BONUS'],
            'table_meta' => $this->tables['BONUS_META'],
            'table_category' => $this->tables['BONUS_CATEGORY'],
            'table_relative' => $this->tables['BONUS_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                $response['body']['posts'] = CardBuilder::bonusCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['close'] = $data->close;
        $newData['wager'] = $data->wager;
        $newData['number_use'] = $data->number_use;
        $newData['value_bonus'] = $data->value_bonus;

        if(empty($data->ref)) $newData['ref'] = [];
        else $newData['ref'] = json_decode($data->ref, true);

        return $newData;
    }
}