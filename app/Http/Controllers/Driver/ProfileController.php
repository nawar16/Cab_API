<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Favorite;
use App\Models\Service;

class ProfileController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth:drivers');
    }
    public function cars(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            $data = $driver->taxis;
            return success_response($data);
        } catch(\Exception $ex){
            return error_response($ex);
        }
    }
    public function profile(Request $request)
    {
        try{
            $driver = auth('drivers')->user();
            if(is_null($driver))
            {
                $data = [
                    'status' => false,
                    'code' => 404,
                    'err' => 'User not found!'
                ];
                return response()->json($data, 404);
            }
            $travels['travels'] = $driver->travels();
            $travels['km_of_travels'] = $driver->km_of_travels();
            $travels['average'] = $driver->rate();
            $travels['balance'] = $driver->balance();
            $data = [
                'profileInfo' => $driver,
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
            $driver = auth('drivers')->user();
            $res = $driver->update($request->newProfile);
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
            $driver = auth('drivers')->user();
            $pass = $request->pass;	
            $res = $driver->update([
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
            $driver = auth('drivers')->user();
            $pic = $request->pic;	
            $res = $driver->update([
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
