<?php

namespace App\Http\Controllers\Api;

use App\Models\Cash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Validate;


class AdminPageController extends Controller
{
    const POST_TYPE   = 'page';
    const OFFSET      = 0;
    const LIMIT       = 8;
    const ORDER_BY    = 'DESC';
    const ORDER_KEY   = 'create_at';
    const LANG        = 1;
    const DEFAULT_SRC = '/img/default.jpg';
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
        $posts = new Pages();
        $settings = [
            'offset'    => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit'     => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by'  => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $arrPosts = $posts->getPosts($settings);
        if(!$arrPosts->isEmpty()) {
            $data = [];
            foreach ($arrPosts as $item) {
                $data[] = self::dataCommonDecode($item);
            }
            $response['body'] = $data;
            $response['confirm'] = 'ok';
            $response['total'] = $posts->getTotalCountByLang($settings['lang']);
            $response['lang'] = config('constants.LANG')[$settings['lang']];
        }
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
        $post = new Pages();
        $data = $post->getPostById($id);
        if(!$data->isEmpty()) {
            $response['body'] = self::dataCommonDecode($data[0]);
            $response['confirm'] = 'ok';
        }
        return response()->json($response);
    }
    public function update(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_request = $request->input('data');
        $data_save = self::dataValidateSave($request->input('data'));
        $post = new Pages();
        $post->updateById($data_request['id'], $data_save);
        Cash::deleteAll();
        return response()->json($response);
    }
    protected static function dataValidateSave($data) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = self::DEFAULT_SRC;
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = self::DEFAULT_SRC;
        }

        return $newData;
    }
    protected static function dataCommonDecode($data) {
        $newData =  [];
        $newData['id']          = $data->id;
        $newData['title']       = htmlspecialchars_decode($data->title);
        $newData['status']      = $data->status;
        $newData['create_at']   = $data->create_at;
        $newData['update_at']   = $data->update_at;
        $newData['slug']        = $data->slug;
        $newData['content']     = $data->content;
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1']          = htmlspecialchars_decode($data->h1);
        $newData['keywords']    = htmlspecialchars_decode($data->keywords);
        $newData['meta_title']  = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc']  = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail']   = $data->thumbnail;
        $newData['post_type']   = $data->post_type;
        return $newData;
    }
}
