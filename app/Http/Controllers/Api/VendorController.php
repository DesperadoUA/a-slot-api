<?php
namespace App\Http\Controllers\Api;

use App\Services\VendorService;

class VendorController extends PostController {
    public function __construct() {
        $this->service = new VendorService();
    }
}