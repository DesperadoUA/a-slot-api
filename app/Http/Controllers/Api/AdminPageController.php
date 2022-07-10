<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PageService;

class AdminPageController extends Controller
{
    const OFFSET      = 0;
    const LIMIT       = 8;
    const ORDER_BY    = 'DESC';
    const ORDER_KEY   = 'create_at';
    const LANG        = 1;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() {
        $this->service = new PageService();
    }
    public function index(Request $request) {
        $settings = [
            'offset'    => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit'     => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by'  => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        return response()->json($this->service->adminIndex($settings));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return response()->json($this->service->adminShow($id));
    }
    public function update(Request $request) {
        return response()->json($this->service->update($request->input('data')));
    }
}
