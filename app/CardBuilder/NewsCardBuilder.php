<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;

class NewsCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    static function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'ref' => json_decode($item->ref, true),
                'create_at' => $item->create_at,
                'short_desc' => $item->short_desc
            ];
        }
        return $posts;
    }
}