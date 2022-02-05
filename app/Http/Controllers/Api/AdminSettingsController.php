<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use App\Validate;

class AdminSettingsController extends Controller
{
    const LANG = 1;
    public function index(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $posts = new Settings();
        $settings = [
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $arrPosts = $posts->getPosts($settings);
        if(!$arrPosts->isEmpty()) {
            $data = [];
            foreach ($arrPosts as $item) {
                $data[] = Validate::componentsLibDecode($item);
            }
            $response['body'] = $data;
            $response['confirm'] = 'ok';
            $response['lang'] = config('constants.LANG')[$settings['lang']];
        }
        return response()->json($response);
    }
    public function show($id) {
        $post = new Settings();
        $data = $post->getPostById($id);
        $response = [
            'body' => $data->isEmpty() ? [] : Validate::componentsLibDecode($data[0]),
            'confirm' => 'ok'
        ];
        return response()->json($response);
    }
    public function update(Request $request) {
        $post = new Settings();
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_request = $request->input('data');
        $data_save = Validate::componentsLibValidateSave($request->input('data'));
        $response['data'] = $data_save;
        $post->updateById($data_request['id'], $data_save);
        return response()->json($response);
    }
}