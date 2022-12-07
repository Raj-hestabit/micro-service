<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class NotificationHandler extends Controller
{
    public $base_url;
    public $token;

    public function __construct(Request $request)
    {
        $this->token    = $request->bearerToken();
        $this->base_url = 'http://user.local/';
    }

    public function notifications(){
        $response   = Http::withToken($this->token)->get($this->base_url.'api/notifications-list');
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function markRead(Request $request){
        $response   = Http::withToken($this->token)->post($this->base_url.'api/notifications/mark-read', $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }
}
