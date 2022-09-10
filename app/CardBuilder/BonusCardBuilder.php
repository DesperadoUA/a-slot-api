<?php
namespace App\CardBuilder;
use App\CardBuilder\BaseCardBuilder;
use App\Models\Posts;
use App\Models\Relative;

class BonusCardBuilder extends BaseCardBuilder {
    function __construct() {
        parent::__construct();
    }
    public function main($arr_posts){
        if(empty($arr_posts)) return [];
        $posts = [];
        $casinoModel = new Posts(['table' => $this->tables['CASINO'], 'table_meta' => $this->tables['CASINO_META']]);
        $casinoCardBuilder = new CasinoCardBuilder();

        foreach ($arr_posts as $item) {
            $casinoIds = Relative::getRelativeByPostId($this->tables['BONUS_CASINO_RELATIVE'], $item->id);
            $casino = $casinoModel->getPublicPostsByArrId($casinoIds);
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
                'casino' => $casino->isEmpty() ? [] : $casinoCardBuilder->defaultCard($casino)[0],
                'value' => $item->value,
                'bonus_system' => json_decode($item->bonus_system, true),
                'ref' => json_decode($item->ref, true),
                'rating' => $item->rating
            ];
        }
        return $posts;
    }
}