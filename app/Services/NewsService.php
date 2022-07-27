<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\NewsCardBuilder;

class NewsService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.NEWS');
        $this->configTables =  [
            'table' => $this->tables['NEWS'],
            'table_meta' => $this->tables['NEWS_META'],
            'table_category' => $this->tables['NEWS_CATEGORY'],
            'table_relative' => $this->tables['NEWS_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new NewsCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $data[0];
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataDeserialize($data[0], $this->shemas);

            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}