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
            $arr_posts = Relative::getRelativeByPostId($this->tables['CASINO_VENDOR_RELATIVE'], $item->id);
            if(!empty($arr_posts)) {
                $vendorPublicPosts = $vendorModel->getPublicPostsByArrId($arr_posts);
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
                'vendors' => $vendorCardBuilder->vendorCasino($vendorPublicPosts)
            ];
        }
        return $posts;
    }
}