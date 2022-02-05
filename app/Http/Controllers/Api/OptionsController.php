<?php

namespace App\Http\Controllers\Api;

use App\Models\Options;
use App\Validate;
use App\Http\Controllers\Controller;

class OptionsController extends Controller
{
    public function index()
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $data = Options::get();
        if(!$data->isEmpty()) {
            $response['body'] = self::dataMetaDecode($data);
            $response['confirm'] = 'ok';
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        foreach ($data as $item) {
            $current_date = Validate::componentsLibDecode($item);
            $newData[] = [
                'key' => $item['key_id'],
                'value' => $current_date['value']
            ];
        }
        return $newData;
    }
}
