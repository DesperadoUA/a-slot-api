<?php
namespace App\Services;
use App\Models\Posts;
use App\Models\Relative;
use App\Services\FrontBaseService;
use App\CardBuilder\VendorCardBuilder;
use App\CardBuilder\CasinoCardBuilder;
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
        $this->configTables =  [
            'table' => $this->tables['CASINO'],
            'table_meta' => $this->tables['CASINO_META'],
            'table_category' => $this->tables['CASINO_CATEGORY'],
            'table_relative' => $this->tables['CASINO_CATEGORY_RELATIVE']
        ];
        $this->cardBuilder = new CasinoCardBuilder();
    }
    public function show($id) {
        $post = new Posts(['table' => $this->configTables['table'], 'table_meta' => $this->configTables['table_meta']]);
        $data = $post->getPublicPostByUrl($id);

        if(!$data->isEmpty()) {
            $this->response['body'] = $this->serialize->frontSerialize($data[0], $this->shemas);

            $this->response['body']['vendors'] = [];
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $data[0]->id);
            if(!empty($arr_posts)) {
                $vendorCardBuilder = new VendorCardBuilder();
                $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
                $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($arr_posts);
                $this->response['body']['vendors'] = $vendorCardBuilder->vendorCasino($vendorPublicPosts);
            }
            $this->response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($this->response));
        }
        return $this->response;
    }
}