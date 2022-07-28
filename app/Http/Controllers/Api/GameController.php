<?php
namespace App\Http\Controllers\Api;

use App\Services\GameService;

class GameController extends PostController {
    public function __construct() {
        $this->service = new GameService();
    }
}