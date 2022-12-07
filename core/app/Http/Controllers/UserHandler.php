<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UserHandler extends Controller
{
    protected $base_url;
    protected $token;


    public function __construct(Request $request)
    {
        $this->token    = $request->bearerToken();
        $this->base_url = 'http://user.local/';
    }

    public function show()
    {
        $response   = Http::withToken($this->token)->get($this->base_url.'api/user');
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function store(Request $request)
    {
        $response   = Http::attach('image', file_get_contents($request->file('profile_picture')), 'profile-image.jpg')
                            ->withToken($this->token)
                            ->post($this->base_url.'api/user', $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }
}
