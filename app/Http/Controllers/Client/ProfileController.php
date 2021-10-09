<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Favorite;

class ProfileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:clients');
    }
    public function profile(Request $request)
    {
        try{
            $client = auth('clients')->user();
            if(is_null($client))
            {
                $data = [
                    'status' => false,
                    'code' => 404,
                    'err' => 'User not found!'
                ];
                return response()->json($data, 404);
            }
            $travels['travels'] = $client->travels();
            $travels['km_of_travels'] = $client->km_of_travels();
            $data = [
                'profileInfo' => $client,
                'travelsInfo' => $travels
            ];
            return success_response($data);
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function update_profile(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $res = $client->update($request->newProfile);
            if($res)
            {
                $data = ['msg' => 'Profile Updated'];
                return success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'err' => 'Server Error'
                ];
                return response()->json($data, 500);
            }
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function change_password(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $pass = $request->pass;	
            $res = $client->update([
                'password' => \Hash::make($pass)
            ]);
            if($res)
            {
                $data = ['msg' => 'Password Updated'];
                return success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'err' => 'Server Error'
                ];
                return response()->json($data, 500);
            }
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function change_pic(Request $request)
    {
        try{
            $client = auth('clients')->user();
            $pic = $request->pic;	
            $res = $client->update([
                'img' => $pic
            ]);
            if($res)
            {
                $data = ['msg' => 'Picture Updated'];
                return success_response($data);
            } else{
                $data = [
                    'status' => false,
                    'code' => 500,
                    'err' => 'Server Error'
                ];
                return response()->json($data, 500);
            }
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
}
