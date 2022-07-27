<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;

class AdminCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    static function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/admin/'.$item->post_type.'/'.$item->id
            ];
        }
        return $posts;
    }
}