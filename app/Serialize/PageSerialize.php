<?php
namespace App\Serialize;
use App\Serialize\BaseSerialize;

class PageSerialize extends BaseSerialize {
    public function adminSerialize($data){
        $newData = self::adminCommonSerialize($data);
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
        $newData['faq'] = empty(json_decode($data->faq, true)) ? [] : json_decode($data->faq, true);
        return $newData;
    }
}