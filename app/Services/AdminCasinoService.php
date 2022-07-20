<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\BaseService;
use App\Serialize\PostSerialize;
use App\Models\Cash;

class AdminCasinoService extends BaseService {
    const SLUG = 'casino';
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.CASINO');
        $this->serialize = new PostSerialize();
    }
   
    public function adminIndex($settings) {
        $posts = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) $data[] = $this->serialize->adminSerialize($item, $this->shemas);
        $this->response['body'] = $data;
        $this->response['confirm'] = 'ok';
        $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function store($data) {
        $data_save = $this->serialize->validateInsert($data, $this->tables['CASINO'], $this->tables['CASINO_META']);
        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
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
        $data_save = $this->serialize->validateUpdate($data, $this->tables['CASINO'], $this->tables['CASINO_META']);
        $post = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
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
}