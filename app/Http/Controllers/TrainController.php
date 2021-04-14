<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Transportation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class TrainController extends Controller
{
    public $response, $user;
    public function __construct (){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAll($limit = NULL, $offset = NULL)
    {
        // GET ALL
        $data["count"] = Transportation::count();
        
        if($limit == NULL && $offset == NULL){
            $data['transportations'] = Transportation::with('category')->where("id_category","=","2")->get();
        }else{
            $data['transportations'] = Transportation::with('category')->where("id_category","=","2")->get()->take($limit)->skip($offset)->get();
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
        $pdepart = $request->pdepart;
        $ptill = $request->ptill;
        $tgl = $request->tgl;
        $data["count"] = Transportation::count();

        if($limit == NULL && $offset == NULL){
            $data["transportations"] = Transportation::where([['p_depart','like', "%$pdepart%"], ['p_till','like', "%$ptill%"], ['departure', 'like', "%$tgl%"]])->orderBy('id_transportation', 'desc')->get();
        } else {
            $data["transportations"] = Transportation::where([['p_depart','like', "%$pdepart%"], ['p_till','like', "%$ptill%"], ['departure', 'like', "%$tgl%"]])->orderBy('id_transportation', 'desc')->take($limit)->skip($offset)->get();
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

        $image = rand().$request->file('image')->getClientOriginalName();
        $request->file('image')->move(base_path("./public/uploads"), $image);

        $data = new Transportation();
        $data->id_category = $request->id_category;
        $data->transportation_name = $request->transportation_name;
        $data->p_depart = $request->p_depart;
        $data->p_till = $request->p_till;
        $data->price = $request->price;
        $data->departure = $request->departure;
        $data->till = $request->till;
        $data->image = $image;
        $data->save();
        $data = Transportation::where('id_transportation','=', $data->id_transportation)->first();

        return $this->response->successResponseData('Insert transportation success', $data);
    }

    //UPDATE
    public function update(Request $request, $id){
        $data = Transportation::where('id_transportation', $id)->first();
        $data->id_category = $request->id_category;
        $data->transportation_name = $request->transportation_name;
        $data->p_depart = $request->p_depart;
        $data->p_till = $request->p_till;
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