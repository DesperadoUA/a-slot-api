<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;

class CasinoCardBuilder extends BaseCardBuilder {
    static function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
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
                'min_payout' => $item->min_payout
            ];
        }
        return $posts;
    }
}