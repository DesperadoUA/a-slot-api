<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CasinoService;

class PostController extends Controller {
    public function __construct() {
        $this->service = new CasinoService();
    }
    public function show($id) {
        return response()->json($this->service->show($id));
    }
    public function category($id){
        return response()->json($this->service->category($id));
    }
}