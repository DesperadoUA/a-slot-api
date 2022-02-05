<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Relative;

class PokerController extends PostController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    const NUMBER_RELATIVE_POSTS = 5;
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
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

            $response['body']['type_payment'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_TYPE_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TYPE_PAYMENT'], 'table_meta' => $this->tables['TYPE_PAYMENT_META']]);
                $response['body']['type_payment'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['technology'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_TECHNOLOGY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TECHNOLOGY'], 'table_meta' => $this->tables['TECHNOLOGY_META']]);
                $response['body']['technology'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['payments'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $response['body']['payments'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['licenses'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_LICENSE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['LICENSE'], 'table_meta' => $this->tables['LICENSE_META']]);
                $response['body']['licenses'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['language'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_LANGUAGE_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['LANGUAGE'], 'table_meta' => $this->tables['LANGUAGE_META']]);
                $response['body']['language'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['currency'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_CURRENCY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CURRENCY'], 'table_meta' => $this->tables['CURRENCY_META']]);
                $response['body']['currency'] = CardBuilder::currencyCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['country'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['POKER_COUNTRY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['COUNTRY'], 'table_meta' => $this->tables['COUNTRY_META']]);
                $response['body']['country'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['body']['pokers'] = [];
            $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
            $settings = [
                'limit' => self::NUMBER_RELATIVE_POSTS,
                'lang' => $data[0]->lang,
                'order_key' => 'rating',
                'exclude' => [$data[0]->id]
            ];
            $arr_posts = $post->getPublicPostsWithOutIds($settings);
            if(!empty($arr_posts)) {
                $response['body']['pokers'] = CardBuilder::pokerCard($arr_posts);
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
            'table' => $this->tables['POKER'],
            'table_meta' => $this->tables['POKER_META'],
            'table_category' => $this->tables['POKER_CATEGORY'],
            'table_relative' => $this->tables['POKER_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['POKER_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
                $response['body']['posts'] = CardBuilder::pokerCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['rating'] = (int)$data->rating;
        $newData['phone'] = $data->phone;
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payments'] = $data->min_payments;
        $newData['email'] = $data->email;
        $newData['year'] = $data->year;
        $newData['site'] = $data->site;
        $newData['withdrawal'] = $data->withdrawal;
        $newData['rakeback'] = $data->rakeback;

        if(empty($data->faq)) $newData['faq'] = [];
        else $newData['faq'] = json_decode($data->faq, true);

        if(empty($data->ref)) $newData['ref'] = [];
        else $newData['ref'] = json_decode($data->ref, true);

        if(empty($data->reviews)) $newData['reviews'] = [];
        else $newData['reviews'] = json_decode($data->reviews, true);

        return $newData;
    }
}