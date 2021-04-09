<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }

    //GET ALL USER
    public function getAll($limit = NULL, $offset = NULL){
        $data["count"] = User::where('role', '=', 'user')->count();
        if($limit == NULL && $offset == NULL){
            $data["user"] = User::where('role', '=', 'user')->get();
        } else {
            $data["user"] = User::where('role', '=', 'user')->take($limit)->skip($offset)->get();
        }
        return $this->response->successData($data);
    }

    //GET USER ID
    public function getById($id){
        $data["user"] = User::where('id', $id)->count();
        return $this->response->successData($data);
    }

    //INSERT
    public function insert(Request $request){
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|min:10',
            'name'=> 'required|string',
            'email'=> 'required|string|unique:Users',
            'password'=> 'required|string|min:6',
            'phone_number'=> 'required|string|min:9',
            'location' => 'required|string|max:255',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }

        $data = new User();
        $data->nik = $request->nik;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->phone_number = $request->phone_number;
        $data->location = $request->location;
        $data->role = 'user';
        $data->save();

        return $this->response->successResponseData('Insert user success', $data);
    }

    //UPDATE
    public function edit(Request $request, $id){
        $data = User::where('id', $id)->first();
        $data->nik = $request->nik;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->phone_number = $request->phone_number;
        $data->location = $request->location;
        $data->save();

        return $this->response->successResponseData('Update data masyarakat success', $data);
    }

    //DELETE USER
    public function destroy($id){
        $delete = User::where('id', $id)->delete();
        if($delete){
            return $this->response->successResponse('Delete transaction success');
        } else {
            return $this->response->errorResponse('Delete transaction failed');
        }
    }
}
