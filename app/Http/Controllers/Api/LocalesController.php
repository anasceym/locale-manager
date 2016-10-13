<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Config;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * This method return name of locale based on the code
     *
     * @param $code
     * @return mixed
     */
    public function getNameByCode($code) {

        if(!$name = Config::get("locale.{$code}")) {

            return response()->json([
                'status_code' => 404,
                'message' => 'Not found'
            ], Response::HTTP_NOT_FOUND);
        }

        $responseData = [
            'code' => $code,
            'name' => $name
        ];

        return response()->json($responseData);
    }
}
