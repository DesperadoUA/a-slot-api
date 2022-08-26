<?php
namespace App\Http\Controllers\Api;

use App\Services\ShareService;

class ShareController extends PostController {
    public function __construct() {
        parent::__construct();
        $this->service = new ShareService();
    }
}