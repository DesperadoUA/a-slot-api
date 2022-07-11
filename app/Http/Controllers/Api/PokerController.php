<?php

namespace App\Http\Controllers\Api;

use App\CardBuilder;
use App\Models\Cash;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\PokerService;

class PokerController extends PostController
{
    public function __construct() {
        $this->service = new PokerService();
    }

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
    public function show($id) {
        return response()->json($this->service->show($id));
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