<?php
namespace App\Services;

use App\Models\Posts;
use App\Models\Cash;

class AdminPokerService extends AdminPostService {
    function __construct() {
        parent::__construct();
        $this->shemas = config('shemas.POKER');
        $this->configTables = [
            'table' => $this->tables['POKER'],
            'table_meta' => $this->tables['POKER_META'],
            'table_category' => $this->tables['POKER_CATEGORY'],
            'table_relative' => $this->tables['POKER_CATEGORY_RELATIVE'],
        ];
    }
    public function store($data) {
        $data_save = $this->serialize->validateInsert($data, $this->tables['POKER'], $this->tables['POKER_META']);
        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
        $this->response['insert_id'] = $post->insert($data_save, $data_meta);
        $this->response['confirm'] = 'ok';
        return $this->response;
    }
    public function adminShow($id) {
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
        $data = $post->getPostById($id);
        if (!empty(count($data))) {
            $this->response['body'] = $this->serialize->adminSerialize($data[0], $this->shemas);
            $this->response['body']['category'] = self::relativeCategoryPost($id, $this->tables['POKER'], 
                                                                                  $this->tables['POKER_CATEGORY'], 
                                                                                  $this->tables['POKER_CATEGORY_RELATIVE']);
            $this->response['confirm'] = 'ok';
        }
        return $this->response;
    }
    public function update($data) {
        $data_save = $this->serialize->validateUpdate($data, $this->tables['POKER'], $this->tables['POKER_META']);
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
        $post->updateById($data['id'], $data_save);

        $data_meta = $this->serialize->validateMetaSave($data, $this->shemas);
        $post->updateMetaById($data['id'], $data_meta);
        self::updateCategory($data['id'], $data['category'], $this->tables['POKER'], 
                                                             $this->tables['POKER_CATEGORY'], 
                                                             $this->tables['POKER_CATEGORY_RELATIVE']);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
    public function delete($data) {
        $post = new Posts(['table' => $this->tables['POKER'], 'table_meta' => $this->tables['POKER_META']]);
        $post->deleteById($data);
        $this->response['confirm'] = 'ok';
        Cash::deleteAll();
        return $this->response;
    }
}