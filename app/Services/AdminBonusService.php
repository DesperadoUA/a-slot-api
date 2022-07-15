<?php
namespace App\Services;
use App\Models\Posts;
use App\Services\BaseService;
use App\Models\Cash;

class AdminBonusService extends BaseService {
    const SLUG = 'bonus';
    function __construct() {
        parent::__construct();
        $this->response = ['body' => [], 'confirm' => 'error'];
        $this->shemas = config('shemas.BONUS');
    }
   
    public function adminIndex($settings) {
        $posts = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $arrPosts = $posts->getPosts($settings);
        $data = [];
        foreach ($arrPosts as $item) {
            $data[] = self::dataCommonDecode($item);
        }
        $this->response['body'] = $data;
        $this->response['confirm'] = 'ok';
        $this->response['total'] = $posts->getTotalCountByLang($settings['lang']);
        $this->response['lang'] = config('constants.LANG')[$settings['lang']];
        return $this->response;
    }
    public function store($data) {
        $data_save = self::dataCommonValidateInsert($data, $this->tables['BONUS'], $this->tables['BONUS_META']);
        $data_meta = self::dataValidateMetaSave($data, $this->shemas);
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = self::dataCommonDecode($data[0]) + self::dataMetaDecode($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['BONUS'], 
                                                                                  $this->tables['BONUS_CATEGORY'], 
                                                                                  $this->tables['BONUS_CATEGORY_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = self::dataValidateSavePosts($data['id'], $data, $this->tables['BONUS'], $this->tables['BONUS_META']);
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = self::dataValidateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['BONUS'], 
                                                             $this->tables['BONUS_CATEGORY'], 
                                                             $this->tables['BONUS_CATEGORY_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function delete($data) {
        $post = new Posts(['table' => $this->tables['BONUS'], 'table_meta' => $this->tables['BONUS_META']]);
        $post->deleteById($data);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    protected static function dataMetaDecode($data, $shemas) {
        $newData = self::dataDeserialize($data, $shemas);  
        return $newData;
    }
    protected static function dataCommonValidateInsert($data, $main_table, $meta_table) {
       $newData = parent::dataCommonValidateInsert($data, $main_table, $meta_table);
       $newData['slug'] = isset($data['slug']) ? $data['slug'] : self::SLUG;
       return $newData;
    }
}