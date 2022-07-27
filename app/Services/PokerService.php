<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\PokerCardBuilder;

class PokerService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.POKER');
        $this->configTables =  [
            'table' => $this->tables['POKER'],
            'table_meta' => $this->tables['POKER_META'],
            'table_category' => $this->tables['POKER_CATEGORY'],
            'table_relative' => $this->tables['POKER_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new PokerCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
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