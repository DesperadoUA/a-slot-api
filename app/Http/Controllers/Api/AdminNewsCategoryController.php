<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Services\AdminNewsCategoryService;

class AdminNewsCategoryController extends BaseController {
    public function __construct() {
        $this->service = new AdminNewsCategoryService();
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
        return response()->json($this->service->store($request->input('data')));
    }
    public function show($id) {
        return response()->json($this->service->adminShow($id));
    }
    public function update(Request $request) {
        return response()->json($this->service->update($request->input('data')));
    }
    public function delete(Request $request) {
        return response()->json($this->service->delete($request->input('data')));
    }
}