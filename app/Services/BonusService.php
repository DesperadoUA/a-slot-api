<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\Models\Cash;
use App\CardBuilder\BonusCardBuilder;
use App\CardBuilder\CasinoCardBuilder;

class BonusService extends FrontBaseService {
    protected $response;
    protected $config;
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.BONUS');
        $this->configTables =  [
            'table' => $this->tables['BONUS'],
            'table_meta' => $this->tables['BONUS_META'],
            'table_category' => $this->tables['BONUS_CATEGORY'],
            'table_relative' => $this->tables['BONUS_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new BonusCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);
            $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
            $casinoCardBuilder = new CasinoCardBuilder();
            $casinoIds = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $data[0]->id);
            $casino = $casinoModel->getPublicPostsByArrId($casinoIds);
            $this->response['body']['casino'] = $casino->isEmpty() ? [] : $casinoCardBuilder->defaultCard($casino)[0];
            if(!empty($this->response['body']['characters'])) {
                $bonus_characters = [];
                foreach($this->response['body']['characters'] as $item) {
                    $bonus_item_characters = [
                        'value' => $item['value'],
                        'child' => []
                    ];
                    $child = [];
                    foreach($item['child'] as $itemChild) {
                        $child[] = [
                            'value_1' => $itemChild['value_1'],
                            'value_2' => explode(',', $itemChild['value_2'])
                        ];
                    }
                    $bonus_item_characters['child'] = $child;
                    $bonus_characters[] = $bonus_item_characters;
                }
                $this->response['body']['characters'] = $bonus_characters;
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}