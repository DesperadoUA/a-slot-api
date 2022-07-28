<?php
namespace App\Http\Controllers\Api;

use App\Services\PokerService;

class PokerController extends PostController
{
    public function __construct() {
        $this->service = new PokerService();
    }
}