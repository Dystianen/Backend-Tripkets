<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ResponseHelper;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;

class CategoryController extends Controller
{
    public $response;
    public function __construct(){
        $this->response = new ResponseHelper();
    }

    //GET ALL USER
    public function getAll($limit = NULL, $offset = NULL){
        $data ["count"] = Category::count();

        if($limit == NULL && $offset == NULL){
            $data['categories'] = Category::get();
        }else{
            $data['categories'] = Category::take($limit)->skip($offset)->get();
        }

        return $this->response->successData($data);
    }

    //GET ID
    public function getById($id){
        $data["categories"] = Category::where('id_category', $id)->get();

        return $this->response->successData($data);
    }

    //DELETE CATEGORY
    public function destroy($id){
        $delete = Category::where('id_category', $id)->delete();
        if($delete){
            return $this->response->successResponse('Delete transaction success');
        } else {
            return $this->response->errorResponse('Delete transaction failed');
        }
    }
}