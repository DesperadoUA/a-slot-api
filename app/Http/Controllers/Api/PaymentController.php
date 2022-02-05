<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Relative;

class PaymentController extends PostController
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
        $post = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

            $response['body']['casino'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['CASINO_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $response['body']['casino'] = CardBuilder::casinoCard($post->getPublicPostsByArrId($arr_posts));
            }

            $response['body']['currency'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['PAYMENT_CURRENCY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CURRENCY'], 'table_meta' => $this->tables['CURRENCY_META']]);
                $response['body']['currency'] = CardBuilder::currencyCard($post->getPublicPostsByArrId($arr_posts));
            }

            $response['body']['type_payment'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['PAYMENT_TYPE_PAYMENT_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['TYPE_PAYMENT'], 'table_meta' => $this->tables['TYPE_PAYMENT_META']]);
                $response['body']['type_payment'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
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
            'table' => $this->tables['PAYMENT'],
            'table_meta' => $this->tables['PAYMENT_META'],
            'table_category' => $this->tables['PAYMENT_CATEGORY'],
            'table_relative' => $this->tables['PAYMENT_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['PAYMENT_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['PAYMENT'], 'table_meta' => $this->tables['PAYMENT_META']]);
                $response['body']['posts'] = CardBuilder::paymentCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['site'] = $data->site;
        $newData['withdrawal'] = $data->withdrawal;
        $newData['commission'] = $data->commission;
        $newData['withdrawal_period'] = $data->withdrawal_period;

        return $newData;
    }
}