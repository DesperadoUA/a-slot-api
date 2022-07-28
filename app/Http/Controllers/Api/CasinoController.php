<?php
namespace App\Http\Controllers\Api;

use App\Services\CasinoService;

class CasinoController extends PostController {
    public function __construct() {
        $this->service = new CasinoService();
    }
}