<?php

namespace App\Http\Controllers\Api;

use App\Models\Cash;
use App\Models\Posts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pages;
use App\Models\Category;
use App\CardBuilder;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected $tables;
    const POST_TYPE = 'page';
    const OFFSET    = 0;
    const LIMIT     = 8;
    const MAIN_PAGE_LIMIT_CASINO = 10;
    const LIMIT_POPULAR_GAME = 5;
    const LIMIT_NEW_GAME = 5;
    const LIMIT_POPULAR_BONUS = 5;
    const LIMIT_NEW_CASINO = 5;
    const CATEGORY_LIMIT_CASINO = 10000;
    const CATEGORY_LIMIT_BONUSES = 10000;
    const CATEGORY_LIMIT_GAMES = 10000;
    const CATEGORY_LIMIT_VENDORS = 10000;
    const CATEGORY_LIMIT_PAYMENT = 10000;
    const CATEGORY_LIMIT_POKER = 10000;
    const MAIN_PAGE_LIMIT_BONUS = 10;
    const ORDER_BY  = 'DESC';
    const ORDER_KEY = 'create_at';
    const LANG      = 1;
    const TABLE = 'pages';
    const TABLE_CASINO = 'casinos';
    const TABLE_CASINO_META = 'casino_meta';
    const TABLE_GAME = 'games';
    const TABLE_GAME_META = 'game_meta';
    const TABLE_BONUS = 'bonuses';
    const TABLE_BONUS_META = 'bonus_meta';
    const TABLE_TYPE_BONUS = 'type_bonuses';
    const TABLE_TYPE_BONUS_META = 'type_bonus_meta';
    const TABLE_VENDOR = 'vendors';
    const TABLE_VENDOR_META = 'vendor_meta';
    const TABLE_PAYMENT = 'payments';
    const TABLE_PAYMENT_META = 'payment_meta';
    const TABLE_POKER = 'pokers';
    const TABLE_POKER_META = 'poker_meta';
    public function __construct() {
        $this->tables = config('tables');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        /*
        $posts = new Pages();
        $settings = [
            'offset'    => $request->has('offset') ? $request->input('offset') : self::OFFSET,
            'limit'     => $request->has('limit') ? $request->input('limit') : self::LIMIT,
            'order_by'  => $request->has('order_by') ? $request->input('order_by') : self::ORDER_BY,
            'order_key' => $request->has('order_key') ? $request->input('order_key') : self::ORDER_KEY,
            'lang'      => $request->has('lang') ? $request->input('lang') : self::LANG
        ];
        $data = $posts->getPublicPosts($settings);
        if(!$data->isEmpty()) {
            $response['body'] = $data;
            $response['confirm'] = 'ok';
        }
        */
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function main()
    {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl('/');
        if(!$data->isEmpty()) {

            $response['body'] = self::dataMetaDecode($data[0]);
            $casino = new Posts(['table' => self::TABLE_CASINO, 'table_meta' => self::TABLE_CASINO_META]);
            $game = new Posts(['table' => self::TABLE_GAME, 'table_meta' => self::TABLE_GAME_META]);
            $bonus = new Posts(['table' => self::TABLE_BONUS, 'table_meta' => self::TABLE_BONUS_META]);

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $response['body']['casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_NEW_CASINO
            ];
            $response['body']['new_casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_NEW_GAME,
                'order_key' => 'rating'
            ];
            $game_list = $game->getPublicPosts($settings);
            $response['body']['top_game'] = CardBuilder::gameCard($game_list);

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::LIMIT_NEW_GAME
            ];
            $game_list = $game->getPublicPosts($settings);
            $response['body']['new_game'] = CardBuilder::gameCard($game_list);

            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::MAIN_PAGE_LIMIT_BONUS
            ];
            $bonus_list = $bonus->getPublicPosts($settings);
            $response['body']['bonuses'] = CardBuilder::bonusCard($bonus_list);

            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function casinos(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.CASINOS'));
        if(!$data->isEmpty()) {
            $casino = new Posts(['table' => self::TABLE_CASINO, 'table_meta' => self::TABLE_CASINO_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'lang'      => $data[0]->lang,
                'limit'     => self::CATEGORY_LIMIT_CASINO,
                'order_key' => 'rating'
            ];
            $response['body']['casino'] = CardBuilder::casinoCard($casino->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function bonuses(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.BONUSES'));
        if(!$data->isEmpty()) {
            $bonus = new Posts(['table' => self::TABLE_BONUS, 'table_meta' => self::TABLE_BONUS_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit' => self::CATEGORY_LIMIT_BONUSES,
                'lang' =>  $data[0]->lang
            ];
            $response['body']['bonuses'] = CardBuilder::bonusCard($bonus->getPublicPosts($settings));
            $response['body']['bonus_type'] = DB::table(self::TABLE_TYPE_BONUS)
                                                  ->where('status', 'public')
                                                  ->where('lang', $data[0]->lang)
                                                  ->select('title')
                                                  ->get();
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function games(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.GAMES'));
        if(!$data->isEmpty()) {
            $game = new Posts(['table' => self::TABLE_GAME, 'table_meta' => self::TABLE_GAME_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_GAMES,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['games'] = CardBuilder::gameCard($game->getPublicPosts($settings));
            $response['body']['category'] = [];
            $settings = [
                'table' => $this->tables['GAME'],
                'table_meta' => $this->tables['GAME_META'],
                'table_category' => $this->tables['GAME_CATEGORY'],
                'table_relative' => $this->tables['GAME_CATEGORY_RELATIVE']
            ];
            $category = new Category($settings);
            $response['body']['category'] = CardBuilder::categoryCard($category->getPublicPosts(), 'games');
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function vendors(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.VENDORS'));
        if(!$data->isEmpty()) {
            $vendor = new Posts(['table' => self::TABLE_VENDOR, 'table_meta' => self::TABLE_VENDOR_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_VENDORS,
                'lang'      =>  $data[0]->lang,
            ];
            $response['body']['vendors'] = CardBuilder::vendorCard($vendor->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function payments(){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.PAYMENTS'));
        if(!$data->isEmpty()) {
            $payment = new Posts(['table' => self::TABLE_PAYMENT, 'table_meta' => self::TABLE_PAYMENT_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_PAYMENT,
                'lang'      =>  $data[0]->lang,
            ];
            $response['body']['payments'] = CardBuilder::paymentCard($payment->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function pokers() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.POKERS'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function countries() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.COUNTRIES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function currencies() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.CURRENCIES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function languages() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.LANGUAGES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function licenses() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.LICENSES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function typePayments() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.TYPE_PAYMENTS'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function technologies() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.TECHNOLOGIES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    public function typeBonuses() {
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];

        $post = new Pages();
        $data = $post->getPublicPostByUrl(config('constants.PAGES.TYPE_BONUSES'));
        if(!$data->isEmpty()) {
            $poker = new Posts(['table' => self::TABLE_POKER, 'table_meta' => self::TABLE_POKER_META]);
            $response['body'] = self::dataMetaDecode($data[0]);
            $settings = [
                'limit'     => self::CATEGORY_LIMIT_POKER,
                'lang'      =>  $data[0]->lang,
                'order_key' => 'rating'
            ];
            $response['body']['poker'] = CardBuilder::pokerCard($poker->getPublicPosts($settings));
            $response['confirm'] = 'ok';
            Cash::store(url()->current(), json_encode($response));
        }
        return response()->json($response);
    }
    protected static function dataMetaDecode($data){
        $newData = [];
        $newData['title'] = htmlspecialchars_decode($data->title);
        $newData['short_desc'] = htmlspecialchars_decode($data->short_desc);
        $newData['h1'] = htmlspecialchars_decode($data->h1);
        $newData['meta_title'] = htmlspecialchars_decode($data->meta_title);
        $newData['description'] = htmlspecialchars_decode($data->description);
        $newData['keywords'] = htmlspecialchars_decode($data->keywords);
        $str = str_replace('<pre', '<div', $data->content);
        $str = str_replace('</pre', '</div', $str);
        $str = str_replace('&nbsp;', '', $str);
        $str = str_replace( '<p><br></p>', '', $str);
        $str = str_replace( '<p></p>', '', $str);
        $newData['content'] = htmlspecialchars_decode($str);
        return $newData;
    }
    public function search(Request $request){
        $response = [
            'body' => [],
            'confirm' => 'error'
        ];
        if($request->has('search_word')){
            $lang = $request->has('lang') ? $request->input('lang') : self::LANG;
            $response['body']['posts'] = CardBuilder::searchCard(
                                             Posts::searchPublicByTitle($lang, $this->tables['CASINO'],
                                             $request->input('search_word'))
            );
            $response['body']['posts'] = array_merge($response['body']['posts'],
                    CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['BONUS'],
                    $request->input('search_word'))
            ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['POKER'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['GAME'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['COUNTRY'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['CURRENCY'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['LANGUAGE'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['LICENSE'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['PAYMENT'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['TECHNOLOGY'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['TYPE_PAYMENT'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['VENDOR'],
                    $request->input('search_word'))
                ));
            $response['body']['posts'] = array_merge($response['body']['posts'],
                CardBuilder::searchCard(Posts::searchPublicByTitle($lang, $this->tables['TYPE_BONUS'],
                    $request->input('search_word'))
                ));
            $response['confirm'] = 'ok';
        }
        return response()->json($response);
    }
    public function siteMap(){
        $response = [
            'body' => [],
            'confirm' => 'ok'
        ];
        $priority = 0.9;
        $data = [];
        $static_page = DB::table($this->tables['PAGES'])
                           ->where('status',  'public')
                           ->where('lang',  self::LANG)
                           ->get();

        foreach ($static_page as $item) {
            $data[] = [
                'url'        => $item->permalink === '/' ? $item->permalink : '/'.$item->permalink,
                'lastmod'    => $item->updated_at,
                'changefreq' => 'daily',
                'priority'   => $item->permalink === '/' ? 1 : $priority
            ];
        }
        $arr_db = [
            ['db' => $this->tables['BONUS'], 'slug' => 'bonus'],
            ['db' => $this->tables['BONUS_CATEGORY'], 'slug' => 'bonuses'],
            ['db' => $this->tables['CASINO'], 'slug' => 'casino'],
            ['db' => $this->tables['CASINO_CATEGORY'], 'slug' => 'casinos'],
            ['db' => $this->tables['POKER'], 'slug' => 'poker'],
            ['db' => $this->tables['POKER_CATEGORY'], 'slug' => 'pokers'],
            ['db' => $this->tables['GAME'], 'slug' => 'game'],
            ['db' => $this->tables['GAME_CATEGORY'], 'slug' => 'games'],
            ['db' => $this->tables['COUNTRY'], 'slug' => 'country'],
            ['db' => $this->tables['COUNTRY_CATEGORY'], 'slug' => 'countries'],
            ['db' => $this->tables['CURRENCY'], 'slug' => 'currency'],
            ['db' => $this->tables['CURRENCY_CATEGORY'], 'slug' => 'currencies'],
            ['db' => $this->tables['LANGUAGE'], 'slug' => 'lang'],
            ['db' => $this->tables['LANGUAGE_CATEGORY'], 'slug' => 'languages'],
            ['db' => $this->tables['LICENSE'], 'slug' => 'license'],
            ['db' => $this->tables['LICENSE_CATEGORY'], 'slug' => 'licenses'],
            ['db' => $this->tables['PAYMENT'], 'slug' => 'payment'],
            ['db' => $this->tables['PAYMENT_CATEGORY'], 'slug' => 'payments'],
            ['db' => $this->tables['TECHNOLOGY'], 'slug' => 'technology'],
            ['db' => $this->tables['TECHNOLOGY_CATEGORY'], 'slug' => 'technologies'],
            ['db' => $this->tables['TYPE_PAYMENT'], 'slug' => 'type-payment'],
            ['db' => $this->tables['TYPE_PAYMENT_CATEGORY'], 'slug' => 'type-payments'],
            ['db' => $this->tables['VENDOR'], 'slug' => 'vendor'],
            ['db' => $this->tables['VENDOR_CATEGORY'], 'slug' => 'vendors'],
            ['db' => $this->tables['TYPE_BONUS'], 'slug' => 'type-bonus'],
            ['db' => $this->tables['TYPE_BONUS_CATEGORY'], 'slug' => 'type-bonuses']
        ];
        foreach ($arr_db as $item) {
            $posts = DB::table($item['db'])
                ->where('status',  'public')
                ->where('lang',  self::LANG)
                ->get();
            foreach ($posts as $post) {
                $data[] = [
                    'url'        => '/'.$item['slug'].'/'.$post->permalink,
                    'lastmod'    => $post->updated_at,
                    'changefreq' => 'daily',
                    'priority'   => 0.8
                ];
            }
        }
        $response['body']['posts'] = $data;
        return response()->json($response);
    }
}



