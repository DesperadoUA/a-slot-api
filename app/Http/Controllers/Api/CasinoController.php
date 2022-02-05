<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Category;
use App\Models\Relative;

class CasinoController extends PostController
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
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

            $response['body']['vendors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $response['body']['vendors'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['type_payment'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_TYPE_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TYPE_PAYMENT'], 'table_meta' => $this->tables['TYPE_PAYMENT_META']]);
                $response['body']['type_payment'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['technology'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_TECHNOLOGY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TECHNOLOGY'], 'table_meta' => $this->tables['TECHNOLOGY_META']]);
                $response['body']['technology'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['payments'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $response['body']['payments'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['licenses'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_LICENSE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['LICENSE'], 'table_meta' => $this->tables['LICENSE_META']]);
                $response['body']['licenses'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['language'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_LANGUAGE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['LANGUAGE'], 'table_meta' => $this->tables['LANGUAGE_META']]);
                $response['body']['language'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['currency'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_CURRENCY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CURRENCY'], 'table_meta' => $this->tables['CURRENCY_META']]);
                $response['body']['currency'] = CardBuilder::currencyCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['country'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_COUNTRY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['COUNTRY'], 'table_meta' => $this->tables['COUNTRY_META']]);
                $response['body']['country'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['bonuses'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
                $response['body']['bonuses'] = CardBuilder::bonusCard($post->getPublicPostsByArrId($arr_posts));
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
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['CASINO_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $response['body']['posts'] = CardBuilder::casinoCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['close'] = $data->close;
        $newData['rating'] = (int)$data->rating;
        $newData['phone'] = $data->phone;
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payments'] = $data->min_payments;
        $newData['email'] = $data->email;
        $newData['chat'] = $data->chat;
        $newData['year'] = $data->year;
        $newData['site'] = $data->site;
        $newData['withdrawal'] = $data->withdrawal;
        $newData['number_games'] = $data->number_games;

        if(empty($data->faq)) $newData['faq'] = [];
        else $newData['faq'] = json_decode($data->faq, true);

        if(empty($data->ref)) $newData['ref'] = [];
        else $newData['ref'] = json_decode($data->ref, true);

        if(empty($data->reviews)) $newData['reviews'] = [];
        else $newData['reviews'] = json_decode($data->reviews, true);

        return $newData;
    }
}