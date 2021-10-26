<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\JsonResponse;
use JWTAuth;
use Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

trait AuthTrait {
    protected $data = [];
    public function __construct()
    {
        $this->data = [
            'status' => false,
            'code' => 401,
            'data' => null,
            'err' => [
                'code' => 1,
                'message' => 'Unauthorized'
                ]
            ];
    }
    public function login(Request $request)
    { 
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);
        $credentials = $request->only(['phone', 'password']);
        $model = $this->model::where('phone','=',$request->phone)->first();
        try {
            if (!$token = JWTAuth::fromUser($model)) {
                throw new \Exception('invalid_credentials');
            }
            $this->data = [
                'status' => true,
                'code' => 200,
                'data' => [
                    '_token' => $token
                ],
                'err' => null
            ]; 
        } catch (\Exception $e) {
            $this->data['err']['message'] = $e->getMessage();
            $this->data['code'] = 401;
        } catch (JWTException $e) {
            $this->data['err']['message'] = 'Could not create token';
            $this->data['code'] = 500;
        }
        return response()->json($this->data, $this->data['code']);
    }
    public function register(RegisterRequest $request)
    {
        $user = new $this->model([
            'fname' => $request->post('fname'),
            'lname' => $request->post('lname'),
            'email' => $request->post('email'),
            'password' => Hash::make($request->post('password')),
            'phone' => $request->post('phone'),
            'credit_card' => $request->post('credit_card'),
            'city' => $request->post('city')
        ]);
        $user->save();
        $res = [
            'status' => true,
            'code' => 200,
            'data' => $user,
            'err' => null
        ];
        return response()->json($res, 200);
    }
    public function detail()
    {
        $this->data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'User' => auth($this->guard)->user()
        ],
            'err' => null
        ];
        return response()->json($this->data);
    }
    public function logout(Request $request)
    {
        $token = $request->token;
        $request->headers->set('Authorization', $token);
        $token = $request->headers->get('Authorization');
        JWTAuth::setToken($token);
        auth($this->guard)->logout();
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                'message' => 'Successfully logged out'
            ],
            'err' => null
        ];
        return response()->json($data);
    }
    public function refresh()
    {
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                '_token' => auth($this->guard)->refresh()
            ],
            'err' => null
        ];
        return response()->json($data, 200);
    }
}