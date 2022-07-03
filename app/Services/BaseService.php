<?php
namespace App\Services;

class BaseService {
    protected $tables;
    public function __construct() {
        $this->tables = config('tables');
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $str = str_replace('<pre', '<div', $data->content);
        $str = str_replace('</pre', '</div', $str);
        $str = str_replace('&nbsp;', '', $str);
        $str = str_replace( '<p><br></p>', '', $str);
        $str = str_replace( '<p></p>', '', $str);
        $newData['content'] = htmlspecialchars_decode($str);
        return $newData;
    }
}