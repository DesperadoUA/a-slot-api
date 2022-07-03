<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Posts;
use App\Validate;
use App\Models\Cash;

class AdminCasinoController extends BaseController
{
    const POST_TYPE = 'casino';
    const MAIN_TABLE = 'casinos';
    const META_TABLE = 'casino_meta';
    const CATEGORY_TABLE = 'casino_category';
    const CATEGORY_RELATIVE = 'casino_category_relative';

    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $posts = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $settings = [
            'offset' => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit' => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by' => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang' => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) {
            $data[] = self::dataCommonDecode($item) + self::dataMetaDecode($item);
        }
        $response['body'] = $data;
        $response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $response['lang'] = config('constants.LANG')[$settings['lang']];

        return response()->json($response);

    }

    public function store(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_save = self::dataValidateInsert($request->input('data'), self::MAIN_TABLE, self::META_TABLE);
        $data_meta = self::dataValidateMetaSave($request->input('data'));
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $response['insert_id'] = $post->insert($data_save, $data_meta);
        $response['data_meta'] = $data_meta;

        return response()->json($response);
    }

    public function show($id)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);
            $response['body']['category'] = self::relativeCategoryPost($id, self::MAIN_TABLE,
                                                                     self::CATEGORY_TABLE,
                                                                      self::CATEGORY_RELATIVE);
            $response['confirm'] = 'ok';
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $data_request = $request->input('data');
        $data_save = self::dataValidateSave($data_request['id'], $request->input('data'), self::MAIN_TABLE, self::META_TABLE);
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $post->updateById($data_request['id'], $data_save);

        $data_meta = self::dataValidateMetaSave($data_request);
        $post->updateMetaById($data_request['id'], $data_meta);
        self::updateCategory($data_request['id'], $data_request['category'], self::MAIN_TABLE, self::CATEGORY_TABLE, self::CATEGORY_RELATIVE);

        Cash::deleteAll();
        return response()->json($response);
    }

    public function delete(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $post = new Posts(['table' => self::MAIN_TABLE, 'table_meta' => self::META_TABLE]);
        $post->deleteById($request->input('data'));
        return response()->json($response);
    }

    protected static function dataValidateMetaSave($data)
    {
        $newData = [];
        $newData['faq'] = isset($data['faq'])
                             ? json_encode($data['faq'])
                             : json_encode([]);

        $newData['rating'] = isset($data['rating'])
                          ? (int)$data['rating']
                          : 0;

        $newData['ref'] = isset($data['ref'])
                             ? json_encode($data['ref'])
                             : json_encode([]);
      
        $newData['licenses'] = isset($data['licenses'])
                             ? json_encode($data['licenses'])
                             : json_encode([]);
        
        $newData['exchange'] = isset($data['exchange'])
                             ? json_encode($data['exchange'])
                             : json_encode([]);

        $newData['events'] = isset($data['events'])
                             ? json_encode($data['events'])
                             : json_encode([]);

        $newData['min_deposit'] = isset($data['min_deposit']) 
                                  ? $data['min_deposit'] 
                                  : '';

        $newData['min_payout'] = isset($data['min_payout']) 
                                  ? $data['min_payout'] 
                                  : '';
        
        return $newData;
    }

    protected static function dataMetaDecode($data)
    {
        $newData = [];
        $newData['faq'] = json_decode($data->faq, true);
        $newData['rating'] = (int)$data->rating;
        $newData['ref'] = json_decode($data->ref, true);
        $newData['exchange'] = json_decode($data->exchange, true);
        $newData['events'] = json_decode($data->events, true);
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payout'] = $data->min_payout;
        if(empty($data->licenses)) {
            $newData['licenses'] = [
                'current_value' => [],
                'all_value' => config('constants.LICENSES')
            ];
        }
        else {
            $newData['licenses'] = [
                'current_value' => json_decode($data->licenses, true),
                'all_value' => config('constants.LICENSES')
            ];
        }
        return $newData;
    }
}