<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TransportationController extends Controller
{
    public $response, $user;
    public function __construct(){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAllTransportation($limit = NULL, $offset = NULL)
    {
        // GET ALL
        $data["count"] = Transportation::count();
        
        if($limit == NULL && $offset == NULL){
            $data['transportations'] = Transportation::with('category')->get();
        }else{
            $data['transportations'] = Transportation::with('category')->take($limit)->skip($offset)->get();
        }
        return $this->response->successData($data);
    }

    //GET BY ID
    public function getByID($id)
    {
        $data["transortations"] = Transportation::where('id_category', $id)->get();
        return $this->response->successData($data);
    }

    //FIND
    public function find(Request $request, $limit = 10, $offset = 0)
    {
        $find = $request->find;
        $data = Transportation::where("id","like","%$find%")
        ->orWhere("id_category","like","%$find%")
        ->orWhere("transportation_name","like","%$find%")
        ->orWhere("price","like","%$find%");
        $data["count"] = $data->count();
        $datas = array();
        foreach ($data->skip($offset)->take($limit)->get() as $p) {
          $item = [
            "id" => $p->id,
            "id_category" => $p->id_category,
            "transportation_name" => $p->transportation_name,
            "price" => $p->price,
            "created_at" => $p->created_at,
            "updated_at" => $p->updated_at
          ];
          array_push($books,$item);
        }
        $data["transportation"] = $datas;
        $data["status"] = 1;
        return $this->response->successResponseData('pencarian anda ada disini1',$data);
    }

    //INSERT
    public function insert(Request $request){
        $validator = Validator::make($request->all(), [
            'id_category' => 'required|string',
            'transportation_name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }
        $data = new Transportation();
        $data->id_category = $request->id_category;
        $data->transportation_name = $request->transportation_name;
        $data->price = $request->price;
        $data->save();

        return $this->response->successResponseData('Insert transportation success', $data);
    }

    //UPDATE
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'id_category' => 'required|string',
            'transportation_name' => 'required|string',
            'price' => 'required|numeric',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }
        $data = Transportation::where('id', $id)->first();
        $data->id_category = $request->id_category;
        $data->transportation_name = $request->transportation_name;
        $data->price = $request->price;
        $data->save();

        return $this->response->successResponseData('Update Transportation success', $data);
    }

    //DELETE
    public function destroy($id){
        $delete = Transportation::where('id', $id)->delete();
        if($delete){
            return $this->response->successResponse('Delete transaction success');
        } else {
            return $this->response->errorResponse('Delete transaction failed');
        }
    }
}
