<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminBettingService;

class AdminBettingController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminBettingService();
    }
}