<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Options;
use App\Validate;

class AdminOptionsController extends Controller
{
    public function index() {
        $post = new Options();
        $data = $post->getAll();
        $response = [
            'body' => $data->isEmpty() ? [] : $data,
            'confirm' => 'ok'
        ];
        return response()->json($response);
    }
    public function show($id) {
        $post = new Options();
        $data = $post->getPostById($id);
        $response = [
            'body' => $data->isEmpty() ? [] : Validate::componentsLibDecode($data[0]),
            'confirm' => 'ok'
        ];
        return response()->json($response);
    }
    public function update(Request $request) {
        $post = new Options();
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_request = $request->input('data');
        $data_save = Validate::componentsLibValidateSave($request->input('data'));
        $post->updateById($data_request['id'], $data_save);
        return response()->json($response);
    }
}
