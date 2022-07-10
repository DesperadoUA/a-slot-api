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
        $this->config = config('constants.PAGES');
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $data[0];
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);

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
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['rating'] = (int)$data->rating;
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payout'] = $data->min_payout;
        
        if(empty($data->faq)) $newData['faq'] = [];
        else $newData['faq'] = json_decode($data->faq, true);

        if(empty($data->ref)) $newData['ref'] = [];
        else $newData['ref'] = json_decode($data->ref, true);

        if(empty($data->exchange)) $newData['exchange'] = [];
        else $newData['exchange'] = json_decode($data->exchange, true);

        if(empty($data->events)) $newData['events'] = [];
        else $newData['events'] = json_decode($data->events, true);

        if(empty($data->slot_category)) $newData['slot_category'] = [];
        else $newData['slot_category'] = json_decode($data->slot_category, true);

        if(empty($data->payment_methods)) $newData['payment_methods'] = [];
        else $newData['payment_methods'] = json_decode($data->payment_methods, true);

        if(empty($data->payment_out_methods)) $newData['payment_out_methods'] = [];
        else $newData['payment_out_methods'] = json_decode($data->payment_out_methods, true);

        if(empty($data->licenses)) $newData['licenses'] = [];
        else $newData['licenses'] = json_decode($data->licenses, true);

        return $newData;
    }
}