<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Posts;
use App\CardBuilder;
class AdminSearchController extends Controller
{
    const LANG = 1;
    const POST_TYPE = 'casino';
    public function index(Request $request) {
        $lang = $request->has('lang') ?  $request->input('lang') : self::LANG;
        $post_type = $request->has('postType') ?  $request->input('postType') : self::POST_TYPE;
        $str = $request->has('searchWord') ?  $request->input('searchWord') : '';
        $response = [
            'body' => CardBuilder::searchAdminCard(Posts::searchByTitle($lang, $post_type, $str)),
            'confirm' => 'ok'
        ];
        return response()->json($response);
    }
}