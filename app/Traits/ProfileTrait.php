<?php

namespace App\Traits;
use Illuminate\Http\Request;

trait ProfileTrait {
    public function profile(Request $request)
    {
        try{
            $model = auth($this->guard)->user();
            if(is_null($model))
            {
                $data = [
                    'status' => false,
                    'code' => 401,
                    'err' => 'User not found!'
                ];
                return response()->json($data, 401);
            }
            $travels['travels'] = $model->travels();
            $travels['km_of_travels'] = $model->km_of_travels();
            $model['img'] = 'localhost:8010/uploads/'.$this->guard.'/' . $model['img'];
            $data = [
                'profileInfo' => $model,
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
            $model = auth($this->guard)->user();
            $res = $model->update($request->newProfile);
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
            $model = auth($this->guard)->user();
            $pass = $request->pass;	
            $res = $model->update([
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
            $model = auth($this->guard)->user();
            $pic = request('pic');	
            $res = $this->upload_image($this->guard, $pic);
            if($res)
            {
                if($model->img)
                {
                    \File::delete('uploads/'.$this->guard.'/'.$model->img);
                }
                $model->update([
                    'img' => $res
                ]);
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