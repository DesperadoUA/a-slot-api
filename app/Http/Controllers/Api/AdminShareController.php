<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminShareService;

class AdminShareController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminShareService();
    }
}