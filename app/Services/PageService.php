<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Pages;
use App\CardBuilder;
use App\Services\BaseService;
use App\Models\Cash;
class PageService extends BaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->config = config('constants.PAGES');
    }
    public function main(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {

            $this->response['body'] = self::dataMetaDecode($data[0]);
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $this->response['body']['casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function casinos(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['CASINO']);
        if(!$data->isEmpty()) {
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::CATEGORY_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $this->response['body']['casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bonuses(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BONUS']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function vendors(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['VENDOR']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function payments(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['PAYMENT']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function pokers(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['POKER']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function news(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['NEWS']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bettings(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BETTING']);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function games(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['GAME']);
        if(!$data->isEmpty()) {
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $this->response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::CATEGORY_LIMIT_GAME
            ];
            $this->response['body']['games'] = CardBuilder::gameCard($game->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function adminIndex($settings) {
        $posts = new Pages();
        $arrPosts = $posts->getPosts($settings);
        if(!$arrPosts->isEmpty()) {
            $data = [];
            foreach ($arrPosts as $item) $data[] = self::dataCommonDecode($item);
            $this->response['body'] = $data;
            $this->response['confirm'] = 'ok';
            $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
            $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        }
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Pages();
        $data = $post->getPostById($id);
        if(!$data->isEmpty()) {
            $this->response['body'] = self::dataCommonDecode($data[0]);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $post = new Pages();
        $data_save = self::dataValidateSave($data);
        $post->updateById($data['id'], $data_save);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    protected static function dataCommonDecode($data){
        $newData = parent::dataCommonDecode($data);
        $newData['faq'] = json_decode($data->faq, true);
        return $newData;
    }
    protected static function dataValidateSave($data){
        $newData = parent::dataValidateSave($data);
        $newData['faq'] = isset($data['faq']) ? json_encode($data['faq']) : json_encode([]);
        return $newData;
    }
}