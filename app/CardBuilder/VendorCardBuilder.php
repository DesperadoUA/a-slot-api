<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class VendorCardBuilder extends BaseCardBuilder {
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
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
    public function vendorCasino($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        $gamePostsModel = new Posts(['table' => $this->tables['GAME'], 'table_meta' => $this->tables['GAME_META']]);
        foreach ($arr_posts as $item) {
            $gamesIds = Relative::getPostIdByRelative($this->tables['GAME_VENDOR_RELATIVE'], $item->id);
            $vendorPublicPosts = $gamePostsModel->getPublicPostsByArrId($gamesIds);
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'number_games' => count($vendorPublicPosts)
            ];
        }
        return $posts;
    }
}