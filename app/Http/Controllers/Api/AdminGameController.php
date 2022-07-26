<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminGameService;

class AdminGameController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminGameService();
    }
}