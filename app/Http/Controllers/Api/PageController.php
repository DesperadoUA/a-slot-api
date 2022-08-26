<?php

namespace App\Http\Controllers\Api;

use App\Models\Posts;
use App\Services\PageService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CardBuilder;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    protected $tables;
    protected $service;
    const LANG = 1;
    public function __construct() {
        $this->tables = config('tables');
        $this->service = new PageService();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = ['body' => [], 'confirm' => 'error'];
        return response()->json($response);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function main(){ 
        return response()->json($this->service->main());
    }
    public function shares(){
        return response()->json($this->service->shares());
    }
    public function bonuses(){
        return response()->json($this->service->bonuses());
    }
    public function games(){
        return response()->json($this->service->games());
    }
    public function search(Request $request){
        $searchWord = $request->has('searchWord') ? $request->input('searchWord') : '';
        $lang = $request->has('lang') ? $request->input('lang') : self::LANG;
        return response()->json($this->service->search($searchWord, $lang));
    }
    public function vendors(){
        return response()->json($this->service->vendors());
    }
    public function pokers(){
        return response()->json($this->service->pokers());
    }
    public function news(){
        return response()->json($this->service->news());
    }
    public function bettings(){
        return response()->json($this->service->bettings());
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



