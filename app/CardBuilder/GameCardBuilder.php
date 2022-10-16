<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class GameCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
        foreach ($arr_posts as $item) {
            $vendorId = Relative::getRelativeByPostId($this->tables['GAME_VENDOR_RELATIVE'], $item->id);
            $vendor = [
                'title' => 'Default'
            ];
            if(count($vendorId)) {
                    $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($vendorId);
                    if(count($vendorPublicPosts)) {
                        $vendor['title'] = $vendorPublicPosts[0]->title;
                    }
            }
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'special_ref' => json_decode($item->special_ref, true),
                'vendor' => $vendor
            ];
        }
        return $posts;
    }
}