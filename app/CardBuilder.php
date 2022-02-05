<?php
namespace App;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Relative;

class CardBuilder {
    const BONUS_CASINO_DB = 'bonus_casino';
    const LENGTH_SHORT_DESC = 50;
    const TABLE_GAME_VENDOR = 'game_vendor_relative';
    const TABLE_BONUS_CASINO = 'bonus_casino_relative';
    const TABLE_VENDOR = 'vendors';
    const TABLE_VENDOR_META = 'vendor_meta';
    const TABLE_CASINO = 'casinos';
    const TABLE_CASINO_META = 'casino_meta';
    const TABLE_TYPE_BONUS = 'type_bonuses';
    const TABLE_TYPE_BONUS_META = 'type_bonus_meta';
    const TABLE_BONUS_TYPE_BONUS = 'bonus_type_bonus_relative';
    const TABLE_CASINO_LICENSE = 'casino_license_relative';
    const TABLE_LICENSE = 'licenses';
    const TABLE_LICENSE_META = 'license_meta';
    static function searchCard($arr_posts) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail,
            ];
        }
        return $posts;
    }
    static function gameCard($arr_slot) {
        if(empty($arr_slot)) return [];
        $posts = [];
        foreach ($arr_slot as $item) {
            $vendor_id = Relative::getRelativeByPostId(self::TABLE_GAME_VENDOR, $item->id);
            $vendors = [];
            if(!empty($vendor_id)) {
                $vendor = new Posts(['table' => self::TABLE_VENDOR, 'table_meta' => self::TABLE_VENDOR_META]);
                $result = $vendor->getPublicPostsByArrId($vendor_id);
                if(!$result->isEmpty()) {
                    $vendors = [
                        'title' => $result[0]->title
                    ];
                }
            }
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'vendor' => $vendors
            ];
        }
        return $posts;
    }
    static function casinoCard($arr_casino) {
        if(empty($arr_casino)) return [];
        $posts = [];
        foreach ($arr_casino as $item) {
            $licensed_id = Relative::getRelativeByPostId(self::TABLE_CASINO_LICENSE, $item->id);
            $licenses = [];
            if(!empty($licensed_id)) {
                $post = new Posts(['table' => self::TABLE_LICENSE, 'table_meta' => self::TABLE_LICENSE_META]);
                $result = $post->getPublicPostsByArrId($licensed_id);
                if(!$result->isEmpty()) {
                    $licenses = [
                        'title' => $result[0]->title
                    ];
                }
            }

            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'title' => $item->title,
                'licenses' => $licenses
            ];
        }
        return $posts;
    }
    static function searchAdminCard($arr_posts) {
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
    static function bonusCard($arr_bonuses){
        if(empty($arr_bonuses)) return [];
        $posts = [];
        foreach ($arr_bonuses as $item) {
            $casino_id = Relative::getRelativeByPostId(self::TABLE_BONUS_CASINO, $item->id);
            $casinos = [];
            if(!empty($casino_id)) {
                $casino = new Posts(['table' => self::TABLE_CASINO, 'table_meta' => self::TABLE_CASINO_META]);
                $result = $casino->getPublicPostsByArrId($casino_id);
                if(!$result->isEmpty()) {
                    $casinos = [
                        'title' => $result[0]->title,
                        'thumbnail' => $result[0]->thumbnail
                    ];
                }
            }

            $type_bonus_id = Relative::getRelativeByPostId(self::TABLE_BONUS_TYPE_BONUS, $item->id);
            $type_bonuses = [];
            if(!empty($type_bonus_id)) {
                $type_bonus = new Posts(['table' => self::TABLE_TYPE_BONUS, 'table_meta' => self::TABLE_TYPE_BONUS_META]);
                $result = $type_bonus->getPublicPostsByArrId($type_bonus_id);
                if(!$result->isEmpty()) {
                    foreach ($result as $type_item) {
                        $type_bonuses[] = [
                            'title' => $type_item->title,
                            'permalink' => '/'.$type_item->slug.'/'.$type_item->permalink,
                        ];
                    }
                }
            }

            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'title' => $item->title,
                'value' => $item->value_bonus,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'casino' => $casinos,
                'type_bonus' => $type_bonuses,
            ];
        }
        return $posts;
    }
    static function vendorCard($arr_vendors){
        if(empty($arr_vendors)) return [];
        $posts = [];
        foreach ($arr_vendors as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
    static function paymentCard($arr_payments){
        if(empty($arr_payments)) return [];
        $posts = [];
        foreach ($arr_payments as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
    static function pokerCard($arr_poker) {
        if(empty($arr_poker)) return [];
        $posts = [];
        foreach ($arr_poker as $item) {
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'rating' => $item->rating,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'ref' => json_decode($item->ref, true),
                'title' => $item->title
            ];
        }
        return $posts;
    }
    static function currencyCard($arr_currency) {
        if(empty($arr_currency)) return [];
        $posts = [];
        foreach ($arr_currency as $item) {
            $posts[] = [
                'thumbnail' => $item->thumbnail,
                'permalink' => '/'.$item->slug.'/'.$item->permalink,
                'title' => $item->title,
                'sub_title' => $item->sub_title
            ];
        }
        return $posts;
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
    static function categoryCard($arr_posts, $slug) {
        if(empty($arr_posts)) return [];
        $posts = [];
        foreach ($arr_posts as $item) {
            $posts[] = [
                'title' => $item->title,
                'permalink' => '/'.$slug.'/'.$item->permalink,
                'thumbnail' => $item->thumbnail
            ];
        }
        return $posts;
    }
}