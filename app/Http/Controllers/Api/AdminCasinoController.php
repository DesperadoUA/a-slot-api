<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminCasinoService;

class AdminCasinoController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminCasinoService();
    }
}