<?php


namespace App;


class Validate
{
    const CONFIG_EDITOR = [
        'TEXT_DECODE' => ['image', 'input', 'rich_text'],
        'JSON_DECODE' => ['two_input_image', 'input_text', 'multiple_menu', 'multiple_two_input'],
    ];
    public static function textValidate($str){
        $str = str_replace('<p></p>', '', $str);
        $str = trim($str);
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        return $str;
    }
    public static function componentsLibValidateSave($data){
        $newData = [];
        $newData['id'] = $data['id'];
        if(in_array($data['editor'], self::CONFIG_EDITOR['TEXT_DECODE'])) {
            $newData['value'] = self::textValidate($data['value']);
        }
        elseif(in_array($data['editor'], self::CONFIG_EDITOR['JSON_DECODE'])) {
            if(empty($data['value'])) {
                $newData['value'] = json_encode([]);
            } else {
                $newData['value'] = json_encode($data['value']);
            }
        }
        return $newData;
    }
    public static function componentsLibDecode($data) {
        $newData =  [];
        $newData['id']     = $data->id;
        $newData['title']  = $data->title;
        $newData['editor'] = $data->editor;
        if(in_array($data->editor, self::CONFIG_EDITOR['TEXT_DECODE'])) {
            $newData['value'] = htmlspecialchars_decode($data->value);
        }
        elseif(in_array($data->editor, self::CONFIG_EDITOR['JSON_DECODE'])) {
            if(empty($data->value)) {
                $newData['value'] = [];
            } else {
                $newData['value'] = json_decode($data->value, true);
            }
        }
        return $newData;
    }
}