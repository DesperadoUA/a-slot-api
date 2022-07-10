<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\BaseService;
use App\Validate;
use App\Models\Cash;

class AdminCasinoService extends BaseService {
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
   
    public function adminIndex($settings) {
        $posts = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) {
            $data[] = self::dataCommonDecode($item) + self::dataMetaDecode($item);
        }
        $this->response['body'] = $data;
        $this->response['confirm'] = 'ok';
        $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function store($data) {
        $data_save = self::dataValidateInsert($data, $this->tables['CASINO'], $this->tables['CASINO_META']);
        $data_meta = self::dataValidateMetaSave($data);
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['data_meta'] = $data_meta;
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0]);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['CASINO'], 
                                                                                  $this->tables['CASINO_CATEGORY'], 
                                                                                  $this->tables['CASINO_CATEGORY_RELATIVE']);
            $this->response['body']['casino_vendor'] = self::relativePostPost($id, $this->tables['CASINO'], 
                                                                                   $this->tables['VENDOR'], 
                                                                                   $this->tables['CASINO_VENDOR_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = self::dataValidateSavePosts($data['id'], $data, $this->tables['CASINO'], $this->tables['CASINO_META']);
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = self::dataValidateMetaSave($data);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['CASINO'], 
                                                             $this->tables['CASINO_CATEGORY'], 
                                                             $this->tables['CASINO_CATEGORY_RELATIVE']);
        self::updatePostPost($data['id'], $data['casino_vendor'], $this->tables['CASINO'], 
                                                                  $this->tables['VENDOR'], 
                                                                  $this->tables['CASINO_VENDOR_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function delete($data) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $post->deleteById($data);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    protected static function dataCommonDecode($data) {
        $newData = parent::dataCommonDecode($data);
        $newData['faq'] = json_decode($data->faq, true);
        return $newData;
    }
    protected static function dataMetaDecode($data) {
        $newData = [];
        $newData['faq'] = json_decode($data->faq, true);
        $newData['rating'] = (int)$data->rating;
        $newData['ref'] = json_decode($data->ref, true);
        $newData['exchange'] = json_decode($data->exchange, true);
        $newData['events'] = json_decode($data->events, true);
        $newData['payment_methods'] = json_decode($data->payment_methods, true);
        $newData['payment_out_methods'] = json_decode($data->payment_out_methods, true);
        $newData['slot_category'] = json_decode($data->slot_category, true);
        $newData['min_deposit'] = $data->min_deposit;
        $newData['min_payout'] = $data->min_payout;
        if(empty($data->licenses)) {
            $newData['licenses'] = [
                'current_value' => [],
                'all_value' => config('constants.LICENSES')
            ];
        }
        else {
            $newData['licenses'] = [
                'current_value' => json_decode($data->licenses, true),
                'all_value' => config('constants.LICENSES')
            ];
        }
        return $newData;
    }
    protected static function dataValidateInsert($data, $main_table, $meta_table) {
        $newData =  [];
        if(isset($data['title'])) {
            $newData['title'] = Validate::textValidate($data['title']);
        }
        else {
            $newData['title'] = '';
        }

        if(isset($data['status'])) {
            $statusArr = ['public', 'hide', 'basket'];
            if(in_array($data['status'], $statusArr)) {
                $newData['status'] = $data['status'];
            } else {
                $newData['status'] = 'public';
            }
        }
        else {
            $newData['status'] = 'public';
        }

        if(isset($data['create_at'])) {
            $newData['create_at'] = $data['create_at'];
        }
        else {
            $newData['create_at'] = date('Y-m-d');
        }

        if(isset($data['update_at'])) {
            $newData['update_at'] = $data['update_at'];
        }
        else {
            $newData['update_at'] = date('Y-m-d');
        }

        if(isset($data['content'])) {
            $newData['content'] = $data['content'];
        }
        else {
            $newData['content'] = '';
        }

        if(isset($data['description'])) {
            $newData['description'] = Validate::textValidate($data['description']);
        }
        else {
            $newData['description'] = '';
        }

        if(isset($data['h1'])) {
            $newData['h1'] = Validate::textValidate($data['h1']);
        }
        else {
            $newData['h1'] = '';
        }

        if(isset($data['keywords'])) {
            $newData['keywords'] = Validate::textValidate($data['keywords']);
        }
        else {
            $newData['keywords'] = '';
        }

        if(isset($data['meta_title'])) {
            $newData['meta_title'] = Validate::textValidate($data['meta_title']);
        }
        else {
            $newData['meta_title'] = '';
        }

        if(isset($data['short_desc'])) {
            $newData['short_desc'] = Validate::textValidate($data['short_desc']);
        }
        else {
            $newData['short_desc'] = '';
        }

        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }

        if(!isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkInsert($data['title'], $main_table, $meta_table);
        }

        if(isset($data['lang'])) {
            if(isset(self::ARR_LANG[$data['lang']])) {
                $newData['lang'] = self::ARR_LANG[$data['lang']];
            } else {
                $newData['lang'] = self::ARR_LANG['ru'];
            }
        }

        if(isset($data['post_type'])) {
            $newData['post_type'] = $data['post_type'];
        } else {
            $newData['post_type'] = self::DEFAULT_POST_TYPE;
        }

        if(isset($data['slug'])) {
            $newData['slug'] = $data['slug'];
        } else {
            $newData['slug'] = self::SLUG;
        }

        return $newData;
    }
    protected static function dataValidateMetaSave($data) {
        $newData = [];
        $newData['faq'] = isset($data['faq']) ? json_encode($data['faq']) : json_encode([]);
        $newData['rating'] = isset($data['rating']) ? (int)$data['rating'] : 0;
        $newData['ref'] = isset($data['ref']) ? json_encode($data['ref']) : json_encode([]);
        $newData['licenses'] = isset($data['licenses']) ? json_encode($data['licenses']) : json_encode([]);
        $newData['exchange'] = isset($data['exchange']) ? json_encode($data['exchange']) : json_encode([]);
        $newData['events'] = isset($data['events']) ? json_encode($data['events']) : json_encode([]);
        $newData['payment_methods'] = isset($data['payment_methods']) ? json_encode($data['payment_methods']) : json_encode([]);
        $newData['payment_out_methods'] = isset($data['payment_out_methods']) ? json_encode($data['payment_out_methods']) : json_encode([]);
        $newData['slot_category'] = isset($data['slot_category']) ? json_encode($data['slot_category']) : json_encode([]);
        $newData['min_deposit'] = isset($data['min_deposit']) ? $data['min_deposit'] : '';
        $newData['min_payout'] = isset($data['min_payout']) ? $data['min_payout'] : '';
        return $newData;
    }
}