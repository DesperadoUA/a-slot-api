<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminPokerService;

class AdminPokerController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminPokerService();
    }
}