<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\Cash;

class AdminGameCategoryController extends BaseController
{
    const POST_TYPE = 'game';
    const MAIN_TABLE = 'games';
    const META_TABLE = 'game_meta';
    const CATEGORY_TABLE = 'game_category';
    const RELATIVE_TABLE = 'game_category_relative';

    public function index(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $category = new Category([
                'table' => self::MAIN_TABLE,
                'table_meta' => self::META_TABLE,
                'table_category' => self::CATEGORY_TABLE,
                'table_relative' => self::RELATIVE_TABLE
            ]
        );
        $settings = [
            'offset' => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit' => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by' => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang' => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $arrPosts = $category->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) {
            $data[] = self::dataCommonDecode($item);
        }
        $response['body'] = $data;
        $response['total'] = $category->getTotalCountByLang($settings['lang']);
        $response['lang'] = config('constants.LANG')[$settings['lang']];

        return response()->json($response);

    }
    public function store(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data_request = $request->input('data');
        $data_save = self::dataValidateCategoryInsert($request->input('data'), self::CATEGORY_TABLE)
                           + self::checkParentCategorySave($data_request, self::CATEGORY_TABLE);
        $response['insert_id'] = DB::table(self::CATEGORY_TABLE)->insertGetId($data_save);

        return response()->json($response);
    }
    public function show($id) {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $category = new Category([
                'table' => self::MAIN_TABLE,
                'table_meta' => self::META_TABLE,
                'table_category' => self::CATEGORY_TABLE,
                'table_relative' => self::RELATIVE_TABLE
            ]
        );
        $data = $category->getPostById($id);
        if (!empty(count($data))) {
            $response['body'] = self::dataCommonDecode($data[0]);
            $response['body']['relative_category'] = self::relativeCategory($data[0]->id);
            $response['confirm'] = 'ok';
        }

        return response()->json($response);
    }
    public function update(Request $request){
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];

        $data_request = $request->input('data');
        $data_save = self::dataValidateCategorySave($data_request['id'], $request->input('data'),
                                  self::CATEGORY_TABLE) + self::checkParentCategorySave($data_request, self::CATEGORY_TABLE);
        $category = new Category([
                'table' => self::MAIN_TABLE,
                'table_meta' => self::META_TABLE,
                'table_category' => self::CATEGORY_TABLE,
                'table_relative' => self::RELATIVE_TABLE
            ]
        );
        $category->updateById($data_request['id'], $data_save);
        Cash::deleteAll();
        return response()->json($response);
    }
    public function delete(Request $request) {
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $data = $request->input('data');
        DB::table(self::CATEGORY_TABLE)->where('id', $data)->delete();
        DB::table(self::CATEGORY_TABLE)->where('parent_id', $data)->update(['parent_id' => 0]);
        return response()->json($response);
    }
    protected static function dataCommonDecode($data) {
        $newData =  parent::dataCommonDecode($data);
        $newData['parent_id'] = $data->parent_id;
        $newData['faq'] = empty(json_decode($data->faq, true))
            ? []
            : json_decode($data->faq, true);
        return $newData;
    }
    protected static function relativeCategory($id) {
        $data = [];
        $post = new Category([
            'table' => self::MAIN_TABLE,
            'table_meta' => self::META_TABLE,
            'table_category' => self::CATEGORY_TABLE,
            'table_relative' => self::RELATIVE_TABLE
        ]);
        $current_post = $post->getPostById($id);
        if($current_post->isEmpty()) {
            return $data;
        }
        else {
            $arr_title_category = [];
            $list_category = $post->getAllPostsByLang($current_post[0]->lang);
            if(!$list_category->isEmpty()) {
                foreach ($list_category as $item) $arr_title_category[] = $item->title;
            }
            $data['all_value'] = $arr_title_category;
            $parent_category = $post->getPostById($current_post[0]->parent_id);
            if($parent_category->isEmpty()) $data['current_value'] = [];
            else $data['current_value'][] = $parent_category[0]->title;
            return $data;
        }
    }
}