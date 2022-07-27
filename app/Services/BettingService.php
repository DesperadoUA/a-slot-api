<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\FrontBaseService;
use App\CardBuilder\BettingCardBuilder;
use App\Models\Cash;

class BettingService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.BETTING');
        $this->configTables =  [
            'table' => $this->tables['BETTING'],
            'table_meta' => $this->tables['BETTING_META'],
            'table_category' => $this->tables['BETTING_CATEGORY'],
            'table_relative' => $this->tables['BETTING_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new BettingCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->tables['BETTING'], 'table_meta' => $this->tables['BETTING_META']]);
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