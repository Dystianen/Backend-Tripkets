<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use JWTAuth;
use DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public $response, $user;
    public function __construct(){
        $this->response = new ResponseHelper();

        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function getAllTransaction($limit = NULL, $offset = NULL)
    {
        //GET ALL
        if($this->user->role == 'user'){
            $data["count"] = Transaction::where('id_user', '=', $this->user->id)->count();

            if($limit == NULL && $offset == NULL){
                $data["transactions"] = Transaction::where('id_user', '=', $this->user->id)->orderBy('check_in', 'desc')->with('user', 'transportation')->get();
            } else {
                $data["transactions"] = Transaction::where('id_user', '=', $this->user->id)->orderBy('check_in', 'desc')->with('user', 'transportation')->take($limit)->skip($offset)->get();
            }
        } else {
            $data["count"] = Transaction::count();

            if($limit == NULL && $offset == NULL){
                $data["transactions"] = Transaction::orderBy('check_in', 'desc')->with('user', 'transportation')->get();
            } else {
                $data["transactions"] = Transaction::orderBy('check_in', 'desc')->with('user', 'transportation')->take($limit)->skip($offset)->get();
            }
        }

        return $this->response->successData($data);
    }

    public function getById($id){
        $data["transactions"] = Transaction::where('id', $id)->with(['user','transportation'])->get();
        return $this->response->successData($data);
    }

    //INSERT TRANSACTION
    public function insert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'check_in' => 'required|string',
            'jumlah'=> 'required|string|max:100',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }

        $transaction = new Transaction();
        $transaction->id_user = $this->user->id;
        $transaction->id_transportation = $request->id_transportation;
        $transaction->id_category = $request->id_category;
        $transaction->jumlah = $request->jumlah;
        $transaction->check_in = $request->check_in;
        $transaction->status = 'booked';
        $transaction->save();

        $data = Transaction::where('id', '=', $transaction->id)->first();
        return $this->response->successResponseData('Transaksi sudah berhasil', $data);
    }

    //UPDATE PESANAN
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'check_in' => 'required',
            'check_out' => 'required',
            'id_transportation' => 'required',
        ]);
        if($validator->fails()){
            return $this->response->errorResponse($validator->errors());
        }

        $data = Transaction::where('id', $request->id)->first();
        $data->check_in = $request->check_in;
        $data->check_out = $request->check_out;
        $data->id_transportation = $request->id_transportation;
        $data->save();

        return $this->response->successReponseData('Update pesanan success');
    }

    //UPDATE STATUS
    public function changeStatus(Request $request, $id){
        $validator = Validator::make($request->all(), [
			'status' => 'required|string',
		]);

		if($validator->fails()){
            return $request->all();
		}
        $data = Transaction::where('id', $id)->first();
        $data->status = $request->status;
        $data->save();

        return $this->response->successResponseData('Update status success', $data);
    }

    //DELETE TRANSACTION
    public function destroy($id){
        $delete = Transaction::where('id', $id)->delete();
        if($delete){
            return $this->response->successResponse('Pesanan dibatalkan');
        } else {
            return $this->response->errorResponse('Pesanan gagal dibatalkan');
        }
    }
}