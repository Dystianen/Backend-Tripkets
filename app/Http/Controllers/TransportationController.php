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
    public function __construct (){
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
    public function getByID($id_transportation)
    {
        $data["transportations"] = Transportation::where('id_transportation', $id_transportation)->get();
        return $this->response->successData($data);
    }

    //FIND
    public function find(Request $request, $limit = 10, $offset = 0)
    {
        $stasiunkeberangkatan = $request->stasiunkeberangkatan;
        $stasiuntujuan = $request->stasiuntujuan;
        $data["count"] = Transportation::count();

        if($limit == NULL && $offset == NULL){
            $data["transportations"] = Transportation::where([['stasiun_keberangkatan','like', "%$stasiunkeberangkatan%"], ['stasiun_tujuan','like', "%$stasiuntujuan%"]])->orderBy('id_transportation', 'desc')->with('category')->get();
        } else {
            $data["transportations"] = Transportation::where([['stasiun_keberangkatan','like', "%$stasiunkeberangkatan%"], ['stasiun_tujuan','like', "%$stasiuntujuan%"]])->orderBy('id_transportation', 'desc')->with('category')->take($limit)->skip($offset)->get();
        }
        return $this->response->successData($data);
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
        $data->stasiun_keberangkatan = $request->stasiun_keberangkatan;
        $data->stasiun_tujuan = $request->stasiun_tujuan;
        $data->price = $request->price;
        $data->departure = $request->departure;
        $data->till = $request->till;
        $data->save();
        $data = Transportation::where('id_transportation','=', $data->id_transportation)->first();

        return $this->response->successResponseData('Insert transportation success', $data);
    }

    //UPDATE
    public function update(Request $request, $id){
        $data = Transportation::where('id_transportation', $id)->first();
        $data->id_category = $request->id_category;
        $data->transportation_name = $request->transportation_name;
        $data->stasiun_keberangkatan = $request->stasiun_keberangkatan;
        $data->stasiun_tujuan = $request->stasiun_tujuan;
        $data->price = $request->price;
        $data->departure = $request->departure;
        $data->till = $request->till;
        $data->save();

        return $this->response->successResponseData('Update Transportation success', $data);
    }

    //DELETE
    public function destroy($id_transportation){
        $delete = Transportation::where('id_transportation', $id_transportation)->delete();
        if($delete){
            return $this->response->successResponse('Delete transaction success');
        } else {
            return $this->response->errorResponse('Delete transaction failed');
        }
    }
}
