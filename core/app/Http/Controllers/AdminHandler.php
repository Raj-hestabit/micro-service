<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminHandler extends Controller
{
    public $base_url;
    public $token;

    public function __construct(Request $request)
    {
        $this->token    = $request->bearerToken();
        $this->base_url = 'http://user.local/';
    }

    public function destory($id){
        $response   = Http::withToken($this->token)->delete($this->base_url.'api/admin/user/'.$id);
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function show($id){
        $response   = Http::withToken($this->token)->get($this->base_url.'api/admin/user/'.$id);
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function assignTeacher(Request $request){
        $response   = Http::withToken($this->token)->post($this->base_url.'api/admin/assign-teacher', $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function pendingRequestUsers(){
        $response   = Http::withToken($this->token)->get($this->base_url.'api/admin/pending-request-users');
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }

    public function approveRequest(Request $request, $id){
        $response   = Http::withToken($this->token)->put($this->base_url.'api/admin/approve-request/'.$id, $request->all());
        $result     = json_decode($response->body());
        return response()->json(!empty($result) ? $result : ['status' => $response->status(),'message'=>'Please try again'], $response->status());
    }
}
