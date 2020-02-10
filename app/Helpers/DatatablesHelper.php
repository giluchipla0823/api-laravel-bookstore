<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class DatatablesHelper
{
    public static function makeResponse(JsonResponse $json){
        $data = $json->getData();
        $data = (array) $data;
        $data['items'] = $data['data'];

        unset($data['data']);

        return $data;
    }
}