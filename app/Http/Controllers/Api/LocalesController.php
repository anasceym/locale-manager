<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;

class LocalesController extends Controller
{
    /**
     * This method should return all locales specified in config
     *  
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {

        return response()->json(Config::get('locale'));
    }
}
