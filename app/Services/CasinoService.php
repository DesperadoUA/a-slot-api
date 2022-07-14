<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Category;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder;
use App\Models\Cash;

class CasinoService extends FrontBaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    const SLUG = 'casino';
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.CASINO');
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $data[0];
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataDeserialize($data[0], $this->shemas);

            $this->response['body']['vendors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $this->response['body']['vendors'] = CardBuilder::defaultCard($post->getPublicPostsByArrId($arr_posts));
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function category($id){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $settings = [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $category = new Category($settings);
        $data = $category->getPublicPostByUrl($id);
        if(!$data->isEmpty()) {
            $response['body'] = $data[0];
            $response['body'] = self::dataCategoryCommonDecode($data[0]);

            $response['body']['posts'] = [];
            $arr_posts = Relative::getPostIdByRelative($this->tables['CASINO_CATEGORY_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
                $response['body']['posts'] = CardBuilder::casinoCard($post->getPublicPostsByArrId($arr_posts));
            }
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
}