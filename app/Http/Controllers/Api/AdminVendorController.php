<?php
namespace App\Http\Controllers\Api;
use App\Services\AdminVendorService;

class AdminVendorController extends AdminPostController
{
    public function __construct() {
        $this->service = new AdminVendorService();
    }
}