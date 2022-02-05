<?php

namespace App\Http\Controllers\Api;

use App\Models\Settings;
use App\Validate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    const LANG = 1;
    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $lang = $request->has('lang') ? $request->input('lang') : self::LANG;
        $data = Settings::where('lang', $lang)->get();
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
