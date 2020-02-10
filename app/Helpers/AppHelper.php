<?php

namespace App\Helpers;

class AppHelper
{
    public static function getIncludesFromUrl(){
        $includes = request()->get('includes');

        if(!$includes){
            return NULL;
        }

        return explode(',', $includes);
    }
}