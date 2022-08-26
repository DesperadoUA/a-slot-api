<?php 
namespace App\Serialize;
use App\Validate;

class BaseSerialize {
    const DEFAULT_SRC = '/img/default.jpg';
    const ARR_LANG = ['ru' => 1, 'ua' => 2];
    const DEFAULT_POST_TYPE = 'default';
    protected static function adminCommonSerialize($data) {
        $newData = [];
        $newData['id']          = $data->id;
        $newData['title']       = htmlspecialchars_decode($data->title);
        $newData['status']      = $data->status;
        $newData['create_at']   = $data->create_at;
        $newData['update_at']   = $data->update_at;
        $newData['slug']        = $data->slug;
        $newData['content']     = json_decode($data->content, true);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1']          = htmlspecialchars_decode($data->h1);
        $newData['keywords']    = htmlspecialchars_decode($data->keywords);
        $newData['meta_title']  = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc']  = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail']   = $data->thumbnail;
        $newData['post_type']   = $data->post_type;
        $newData['permalink']   = $data->permalink;
        $newData['lang']        = $data->lang;
        return $newData;
    }
    protected static function commonValidateInsert($data) {
        $newData =  [];
        $newData['title'] = isset($data['title']) ? Validate::textValidate($data['title']) : '';
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
        $newData['create_at']   = isset($data['create_at']) ? $data['create_at'] : date('Y-m-d');
        $newData['update_at']   = isset($data['update_at']) ? $data['update_at'] : date('Y-m-d');
        $newData['content']     = empty($data['content']) ? json_encode([]) : json_encode($data['content']);
        $newData['description'] = isset($data['description']) ? Validate::textValidate($data['description']) : '';
        $newData['h1']          = isset($data['h1']) ? Validate::textValidate($data['h1']) : '';
        $newData['keywords']    = isset($data['keywords']) ? Validate::textValidate($data['keywords']) : '';
        $newData['meta_title']  = isset($data['meta_title']) ? Validate::textValidate($data['meta_title']) : '';
        $newData['short_desc']  = isset($data['short_desc']) ? Validate::textValidate($data['short_desc']) : '';
        if(isset($data['thumbnail'])) {
            if(empty($data['thumbnail'])) $newData['thumbnail'] = config('constants.DEFAULT_SRC');
            else $newData['thumbnail'] = $data['thumbnail'];
        }
        else {
            $newData['thumbnail'] = config('constants.DEFAULT_SRC');
        }
        if(isset($data['lang'])) {
            if(isset(self::ARR_LANG[$data['lang']])) {
                $newData['lang'] = self::ARR_LANG[$data['lang']];
            } else {
                $newData['lang'] = self::ARR_LANG['ru'];
            }
        }
        $newData['post_type']  = isset($data['post_type']) ? $data['post_type'] : self::DEFAULT_POST_TYPE;
        return $newData;
    }
    protected static function frontCommonSerialize($data) {
        $newData = [];
        $newData['id'] = $data->id;
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['status'] = $data->status;
        $newData['create_at'] = $data->create_at;
        $newData['update_at'] = $data->update_at;
        $newData['slug'] = $data->slug;
        $newData['content'] = json_decode($data->content, true);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['thumbnail'] = $data->thumbnail;
        $newData['permalink'] = $data->permalink;
        $newData['post_type'] = $data->post_type;
        return $newData;
    }
}