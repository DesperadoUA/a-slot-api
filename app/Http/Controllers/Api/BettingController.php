<?php
namespace App\Http\Controllers\Api;

use App\Services\BettingService;

class BettingController extends PostController {
    public function __construct() {
        parent::__construct();
        $this->service = new BettingService();
    }
}