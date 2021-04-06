<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }

    //REGISTER
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|min:10',
            'name'=> 'required|string|max:255',
            'email'=> 'required|string|max:50|unique:users',
            'password'=> 'required|string|min:6',
            'phone_number' => 'required|string|min:9',
            'location' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }

        $user = new User();
        $user->nik = $request->nik;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->phone_number = $request->phone_number;
        $user->location = $request->location;
        $user->role = 'user';
        $user->save();

        $token = JWTAuth::fromUser($user);
        $data = User::where('email', '=', $request->email)->first();
        return $this->response->successResponseData('Data berhasil teregristasi', $data);
    }

    //LOGIN
    public function login(Request $request){
		$credentials = $request->only('email', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)){
                return $this->response->errorResponse('Invalid email and password');
			}
		} catch(JWTException $e){
            return $this->response->errorResponse('Generate Token Failed');
		}

        $data = [
			'token' => $token,
			'user'  => JWTAuth::user()
		];
        return $this->response->successResponseData('Authentication success', $data);
	}

    //LOGIN CHECK
	public function loginCheck(){
		try {
			if(!$user = JWTAuth::parseToken()->authenticate()){
				return $this->response->errorResponse('Invalid token!');
			}
		} catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
			return $this->response->errorResponse('Token expired!');
		} catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
			return $this->response->errorResponse('Invalid token!');
		} catch (Tymon\JWTAuth\Exceptions\JWTException $e){
			return $this->response->errorResponse('Token absent!');
		}
		
		return $this->response->successResponseData('Authentication success!', $user);
	}

    //LOGOUT
    public function logout(Request $request)
    {
        if(JWTAuth::invalidate(JWTAuth::getToken())) {
            return $this->response->successResponse('You are logged out');
        } else {
            return $this->response->errorResponse('Logged out failed');
        }
    }
}
