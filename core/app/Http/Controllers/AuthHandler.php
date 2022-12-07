<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthHandler extends Controller
{
    public $base_url;

    public function __construct()
    {
        $this->base_url = 'http://user.local/';
    }

    public function register(Request $request)
    {
        $response   = Http::post($this->base_url.'api/register', $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function login(Request $request)
    {
        $response   = Http::post($this->base_url.'api/login', $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }
}
