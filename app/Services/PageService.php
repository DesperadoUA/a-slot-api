<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Pages;
use App\Serialize\PageSerialize;
use App\Services\BaseService;
use App\Models\Cash;
use App\CardBuilder\CasinoCardBuilder;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\GameCardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\CardBuilder\NewsCardBuilder;
class PageService extends BaseService {
    protected $response;
    protected $config;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const MAIN_PAGE_LIMIT_BONUSES = 1000;
    const MAIN_PAGE_LIMIT_NEWS = 1000;
    const CATEGORY_LIMIT_CASINO = 1000;
    const CATEGORY_LIMIT_GAME = 1000;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->config = config('constants.PAGES');
        $this->serialize = new PageSerialize();
    }
    public function main(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {
            $casinoCardBuilder = new CasinoCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $casino = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $this->response['body']['casino'] = $casinoCardBuilder->main($casino->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function shares(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['SHARE']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bonuses(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BONUS']);
        if(!$data->isEmpty()) {
            $bonusCardBuilder = new BonusCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $bonusModel = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_BONUSES,
                'order_key' => 'rating'
            ];
            $this->response['body']['bonus'] = $bonusCardBuilder->main($bonusModel->getPublicPosts($settings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function vendors(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['VENDOR']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function pokers(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['POKER']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function news(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['NEWS']);
        if(!$data->isEmpty()) {
            $newsCardBuilder = new NewsCardBuilder();
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $bonusModel = new Posts(['table' => $this->tables['NEWS'], 'table_meta' => $this->tables['NEWS_META']]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_NEWS,
            ];
            $this->response['body']['news'] = $newsCardBuilder->main($bonusModel->getPublicPosts($settings));

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function bettings(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['BETTING']);
        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function games(){
        $post = new Pages();
        $data = $post->getPublicPostByUrl($this->config['GAME']);
        if(!$data->isEmpty()) {
            $gameCardBuilder = new GameCardBuilder();
            $game = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
            $this->response['body'] = $this->serialize->frontSerialize($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::CATEGORY_LIMIT_GAME
            ];
            $this->response['body']['games'] = $gameCardBuilder->main($game->getPublicPosts($settings));
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
    public function search($searchWord, $lang) {
        if(empty($searchWord)) {
            $this->response['body']['posts'] = [];
            $this->response['confirm'] = 'ok';
        } else {
            $arrDb = [
                'CASINO', 'BONUS', 'POKER', 'GAME', 'BETTING', 'NEWS', 'VENDOR', 'SHARES'
            ];
            $posts = [];
            foreach($arrDb as $db) {
                $posts = array_merge($posts, BaseCardBuilder::defaultCard(Posts::searchPublicByTitle($lang, $this->tables[$db], $searchWord)));
            }
            $this->response['body']['posts'] = $posts;
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function adminIndex($settings) {
        $posts = new Pages();
        $arrPosts = $posts->getPosts($settings);
        if(!$arrPosts->isEmpty()) {
            $data = [];
            foreach ($arrPosts as $item) $data[] = $this->serialize->adminSerialize($item);
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
            $this->response['body'] = $this->serialize->adminSerialize($data[0]);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $post = new Pages();
        $data_save = $this->serialize->validateUpdate($data);
        $post->updateById($data['id'], $data_save);
        $this->response['confirm'] = 'ok';
        $this->response['test'] = $data_save;
        Cash::deleteAll();
        return $this->response;
    }
}