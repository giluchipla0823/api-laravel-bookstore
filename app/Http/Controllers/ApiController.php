<?php

namespace App\Http\Controllers;


use App\Traits\ApiResponser;
use App\Traits\ResponseTransformer;

class ApiController extends Controller
{
    use ApiResponser, ResponseTransformer;
}