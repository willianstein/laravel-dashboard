<?php

namespace App\Http\Controllers\App\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Partners;

class HorusConfig extends Controller {
    public function index() {
        $dropPartners = $dropPartners = Partners::get(['id','name'])->toArray();
        return view('app.integrations.horusConfig', compact('dropPartners'));
    }

}
