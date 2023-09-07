<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

trait Helper
{

    //use in view
    // {!! Helper::shout('exemplo de uso de helper!!') !!}

    // use controller
    // Helper::shout('now i\'m using my helper class in a controller!!');


    public static function isAdmin()
    {
        $roles = Auth::user()->getRoleNames()->toArray();

        if(!in_array('PARCEIRO', $roles))
            return true;

            return false;
    }

    public static function isPartner()
    {
        $roles = Auth::user()->getRoleNames()->toArray();

        if(in_array('PARCEIRO', $roles))
            return true;

            return false;
    }
}
