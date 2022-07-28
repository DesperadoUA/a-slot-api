<?php
namespace App\Services;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Category;
use App\Models\Relative;
use App\Models\Posts;
use App\Models\Cash;
use App\Serialize\PostSerialize;

class FrontBaseService
{
    const OFFSET = 0;
    const ORDER_BY = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG = 1;
    protected $request;
    protected $tables;

    public function __construct() {
        $this->tables = config('tables');
        $this->cardBuilder = new CasinoCardBuilder();
        $this->configTables =  [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new CasinoCardBuilder();
        $this->serialize = new PostSerialize();
    }
    protected static function dataCommonDecode($data) {
        $newData = [];
        $newData['id'] = $data->id;
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['status'] = $data->status;
        $newData['create_at'] = $data->create_at;
        $newData['update_at'] = $data->update_at;
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
    protected static function dataCategoryCommonDecode($data) {
        $newData = self::dataCommonDecode($data);
        $newData['faq'] = empty(json_decode($data->faq, true))
            ? []
            : json_decode($data->faq, true);
        return $newData;
    }
    protected static function dataDeserialize($data, $shemas) {
        $newData = [];
        foreach ($shemas as $key => $field) {
            if($field['type'] === 'number') {
                $newData[$key] = (int)$data->{$key};
            }
            elseif($field['type'] === 'string') {
                $newData[$key] = $data->{$key};
            }
            elseif($field['type'] === 'json') {
                if(empty($data->{$key})) $newData['exchange'] = [];
                else $newData[$key] = json_decode($data->{$key}, true);
            }
        }
        return $newData;
    }
    public function category($id) {
        $category = new Category($this->configTables);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = $data[0];
            $this->response['body'] = self::dataCategoryCommonDecode($data[0]);

            $this->response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->configTables['table_relative'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
                $this->response['body']['posts'] = $this->cardBuilder->main($post->getPublicPostsByArrId($arr_posts));
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}