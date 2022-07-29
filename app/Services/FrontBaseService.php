<?php
namespace App\Services;
use App\CardBuilder\CasinoCardBuilder;
use App\Models\Category;
use App\Models\Relative;
use App\Models\Posts;
use App\Models\Cash;
use App\Serialize\PostSerialize;
use App\Serialize\CategorySerialize;

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
        $this->categorySerialize = new CategorySerialize();
    }
    public function category($id) {
        $category = new Category($this->configTables);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->categorySerialize->frontSerialize($data[0]);

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