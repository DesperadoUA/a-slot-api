<?php
namespace App\Services;
use App\Validate;
use App\Models\Posts;
use Illuminate\Support\Facades\DB;
use App\Models\Relative;
use App\Models\Category;

class BaseService {
    const DEFAULT_SRC = '/img/default.jpg';
    const ARR_LANG = ['ru' => 1, 'ua' => 2];
    const DEFAULT_POST_TYPE = 'default';
    protected $tables;
    public function __construct() {
        $this->tables = config('tables');
    }
}
