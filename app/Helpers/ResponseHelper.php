<?php
namespace App\Helpers;

class ResponseHelper
{
    public function successResponseData($message,$data) {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data
        ]);
    }

    public function successData($data) {
        return response()->json([
            'status' => true,
            'data'   => $data
        ]);
    }

    public function successResponse($message) {
        return response()->json([
            'status'  => true,
            'message' => $message
        ]);
    }

    public function errorResponse($message) {
        return response()->json([
            'status'  => false,
            'message' => $message
        ]); 
    }
    public function expiredResponse($message) {
        return response()->json([
            'status'  => false,
            'message' => $message
        ]); 
    }
}