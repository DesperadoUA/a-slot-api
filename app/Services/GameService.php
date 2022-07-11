<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Category;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder;
use App\Models\Cash;

class GameService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->config = config('constants.PAGES');
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $data[0];
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function _category($id){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        /*$settings = [
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
        */
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['iframe'] = $data->iframe;

        return $newData;
    }
}