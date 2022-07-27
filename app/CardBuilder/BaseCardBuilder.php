<?php
namespace App\CardBuilder;

class BaseCardBuilder {
    public function __construct() {
        $this->tables = config('tables');
    }
    static function defaultCard($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
}