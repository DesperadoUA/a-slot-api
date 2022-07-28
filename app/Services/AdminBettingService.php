<?php
namespace App\Services;

use App\Models\Posts;
use App\Models\Cash;

class AdminBettingService extends AdminPostService {
    function __construct() {
        parent::__construct();
        $this->shemas = config('shemas.BETTING');
        $this->configTables = [
            'table' => $this->tables['BETTING'],
            'table_meta' => $this->tables['BETTING_META'],
            'table_category' => $this->tables['BETTING_CATEGORY'],
            'table_relative' => $this->tables['BETTING_CATEGORY_RELATIVE'],
        ];
    }
    public function store($data) {
        $data_save = $this->serialize->validateInsert($data, $this->tables['BETTING'], $this->tables['BETTING_META']);
        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post = new Posts(['table' => $this->tables['BETTING'], 'table_meta' => $this->tables['BETTING_META']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['BETTING'], 'table_meta' => $this->tables['BETTING_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['BETTING'], 
                                                                                  $this->tables['BETTING_CATEGORY'], 
                                                                                  $this->tables['BETTING_CATEGORY_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = $this->serialize->validateUpdate($data, $this->tables['BETTING'], $this->tables['BETTING_META']);
        $post = new Posts(['table' => $this->tables['BETTING'], 'table_meta' => $this->tables['BETTING_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['BETTING'], 
                                                             $this->tables['BETTING_CATEGORY'], 
                                                             $this->tables['BETTING_CATEGORY_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
}