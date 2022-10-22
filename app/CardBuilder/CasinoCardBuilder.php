<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class CasinoCardBuilder extends BaseCardBuilder {
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $vendorPublicPosts = [];
        $vendorModel = new Posts(['table' => $this->tables['VENDOR'], 'table_meta' => $this->tables['VENDOR_META']]);
        $vendorCardBuilder = new VendorCardBuilder();
        foreach ($arr_posts as $item) {
            $vendor_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $item->id);
            if(!empty($vendor_posts)) {
                $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($vendor_posts);
            }
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'title' => $item->title,
                'licenses' => json_decode($item->licenses, true),
                'exchange' => json_decode($item->exchange, true),
                'events' => json_decode($item->events, true),
                'min_deposit' => $item->min_deposit,
                'min_payout' => $item->min_payout,
                'active_languages' => empty(json_decode($item->active_languages, true)) ? [] : json_decode($item->active_languages, true),
                'vendors' => $vendorCardBuilder->vendorCasino($vendorPublicPosts),
                'convenience' => $item->convenience,
                'replenishment' => $item->replenishment,
                'support' => $item->support,
                'actions' => $item->actions
            ];
        }
        return $posts;
    }
    public function sliderCard($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                    'title' => $item->title,
                    'permalink' => '/'.$item->slug.'/'.$item->permalink,
                    'thumbnail' => $item->thumbnail,
                    'ref' => json_decode($item->ref, true),
            ];
        }
        return $posts;
    }
}