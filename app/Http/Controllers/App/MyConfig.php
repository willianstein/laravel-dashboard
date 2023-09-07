<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Partners;

class MyConfig extends Controller {

    public function index() {
        return view('app.myConfig');
    }

}
