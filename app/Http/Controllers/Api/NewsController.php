<?php
namespace App\Http\Controllers\Api;

use App\Services\NewsService;

class NewsController extends PostController {
    public function __construct() {
        $this->service = new NewsService();
    }
}