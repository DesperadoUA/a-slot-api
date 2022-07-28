<?php
namespace App\Http\Controllers\Api;

use App\Services\BonusService;

class BonusController extends PostController {
    public function __construct() {
        $this->service = new BonusService();
    }
}