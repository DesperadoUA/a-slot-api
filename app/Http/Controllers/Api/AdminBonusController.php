<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminBonusService;

class AdminBonusController extends AdminPostController {
    public function __construct() {
        $this->service = new AdminBonusService();
    }
}