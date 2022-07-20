<?php
namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Cash;
use Illuminate\Support\Facades\DB;
use App\Services\AdminBettingCategoryService;

class AdminBettingCategoryController extends BaseController
{
    const POST_TYPE = 'betting';
    const MAIN_TABLE = 'bettings';
    const META_TABLE = 'betting_meta';
    const CATEGORY_TABLE = 'betting_category';
    const RELATIVE_TABLE = 'betting_category_relative';
    public function __construct() {
        $this->service = new AdminBettingCategoryService();
    }
    public function index(Request $request) {
        $settings = [
            'offset' => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit' => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by' => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang' => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        return response()->json($this->service->adminIndex($settings));

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
        return response()->json($this->service->adminShow($id));
    }
    public function update(Request $request) {
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
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        return $newData;
    }
}