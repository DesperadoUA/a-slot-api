<?php
namespace App\Serialize;
use App\Serialize\BaseSerialize;

class CategorySerialize extends BaseSerialize {
    public function adminSerialize($data){
        $newData = self::adminCommonSerialize($data);
        $newData['parent_id'] = $data->parent_id;
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        return $newData;
    }
    public function validateUpdate($data){
        $newData = self::commonValidateInsert($data);
        $newData['faq'] = json_encode($data['faq']);
        return $newData;
    }
    public function frontSerialize($data) {
        $newData = self::frontCommonSerialize($data);
        $newData['parent_id'] = $data->parent_id;
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        return $newData;
    }
}