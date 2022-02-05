<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    protected $request;
    protected $tables;

    public function __construct(Request $request) {
        $this->request = $request;
        $this->tables = config('tables');
    }
    const OFFSET = 0;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;

    protected static function dataCommonDecode($data)
    {
        $newData = [];
        $newData['id'] = $data->id;
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['status'] = $data->status;
        $newData['created_at'] = $data->created_at;
        $newData['updated_at'] = $data->updated_at;
        $newData['slug'] = $data->slug;
        $str = str_replace('<pre', '<div', $data->content);
        $str = str_replace('</pre', '</div', $str);
        $str = str_replace('&nbsp;', '', $str);
        $str = str_replace('<p><br></p>', '', $str);
        $str = str_replace('<p></p>', '', $str);
        $newData['content'] = htmlspecialchars_decode($str);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail'] = $data->thumbnail;
        $newData['permalink'] = $data->permalink;
        $newData['post_type'] = $data->post_type;
        return $newData;
    }
    protected static function dataCategoryCommonDecode($data){
        $newData = self::dataCommonDecode($data);
        $newData['faq'] = empty(json_decode($data->faq, true))
            ? []
            : json_decode($data->faq, true);
        return $newData;
    }
}