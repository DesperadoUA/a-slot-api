<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminNewsService;

class AdminNewsController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminNewsService();
    }
}