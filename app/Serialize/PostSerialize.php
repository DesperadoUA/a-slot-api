<?php
namespace App\Serialize;
use App\Models\Posts;
use App\Serialize\BaseSerialize;

class PostSerialize extends BaseSerialize {
    public function adminSerialize($data, $shemas){
        $newData = array_merge(self::adminCommonSerialize($data), self::dataSerialize($data, $shemas));
        return $newData;
    }
    protected static function dataSerialize($data, $shemas){
        $newData = [];
        foreach ($shemas as $key => $field) {
            if($field['type'] === 'number') {
                $newData[$key] = (int)$data->{$key};
            }
            elseif($field['type'] === 'string') {
                $newData[$key] = $data->{$key};
            }
            elseif($field['type'] === 'json') {
                if(empty($data->{$key})) $newData['exchange'] = [];
                else $newData[$key] = json_decode($data->{$key}, true);
            }
        }
        return $newData;
    }
    public function validateInsert($data, $main_table, $meta_table){
        $newData = self::commonValidateInsert($data);
        $newData['permalink'] =  self::permalinkInsert($data['title'], $main_table, $meta_table);
        return $newData;
    }
    public function validateUpdate($data, $main_table, $meta_table){
        $newData = self::commonValidateInsert($data);
        if(isset($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($data['id'], $data['permalink'], $main_table, $meta_table);
        }
        elseif (empty($data['permalink'])) {
            $newData['permalink'] = self::permalinkUpdate($data['id'], $data['title'], $main_table, $meta_table);
        }
        else {
            $newData['permalink'] = self::permalinkUpdate($data['id'], $data['title'], $main_table, $meta_table);
        }
        return $newData;
    }
    public static function validateMetaSave($data, $shemas) {
        $newData = [];
        foreach ($shemas as $key => $field) {
            if(isset($data[$key])) {
                if($shemas[$key]['type'] === 'json'){
                    $newData[$key] = json_encode($data[$key]);
                }
                elseif($shemas[$key]['type'] === 'number'){
                    $newData[$key] = (int)$data[$key];
                }
                elseif($shemas[$key]['type'] === 'string') {
                    $newData[$key] = $data[$key];
                }
            }
            else {
                if($shemas[$key]['type'] === 'json'){
                    $newData[$key] = json_encode($shemas[$key]['default']);
                } else {
                    $newData[$key] = $shemas[$key]['default'];
                }
            }
        }
        return $newData;
    }
    protected static function permalinkInsert($permalink, $main_table, $meta_table) {
        $permalink = str_slug($permalink);
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return $permalink;
        }
        else {
            $counter = 0;
            do {
                $counter++;
                $new_permalink = $permalink.'-'.$counter;
                $new_candidate = $post->getByPermalink($new_permalink);
                if($new_candidate->isEmpty()) break;
            } while (true);
            return $new_permalink;
        }
    }
    protected static function permalinkUpdate($id, $permalink, $main_table, $meta_table) {
        $post = new Posts(['table' => $main_table, 'table_meta' => $meta_table]);
        $candidate = $post->getByPermalink($permalink);
        if($candidate->isEmpty()) {
            return str_slug($permalink);
        }
        else {
            if($candidate[0]->id === $id) return $permalink;
            else {
                $counter = 0;
                do {
                    $counter++;
                    $new_permalink = $permalink.'-'.$counter;
                    $new_candidate = $post->getByPermalink($new_permalink);
                    if($new_candidate->isEmpty()) break;
                } while (true);
                return str_slug($new_permalink);
            }
        }
    }
}