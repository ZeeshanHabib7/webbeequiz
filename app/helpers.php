<?php

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (! function_exists('successResponse')) {
    /**
     * @param $data
     * @param string $message
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    function successResponse($data = [], $message = '',$paginate = FALSE,$code=200){

        if($paginate == TRUE && is_object($data)){
          $data =  paginate($data);
        }

        $response = [
            'success' => true,
            'status_code'    =>$code,
            'message' => [$message],
            'data'   => $data
        ];
        return response()->json($response, 200);
    }

}

if (! function_exists('errorResponse')){
    /**
     * @param $error
     * @param string $message
     * @param int $status_code
     * @return \Illuminate\Http\JsonResponse
     */
    function errorResponse($message, int $code = 400, array $data = []){

        $code = $code == 0 ? 400 : $code;
        $response = [
            'success' => false,
            'status'    => $code,
            'message' => $message,
            'data'=> $data
        ];
        if($code == 422){
            $response['data'] = $data;
        }

        $code = is_int($code) && $code != 0 ? $code : 500;

        return response()->json($response, $code);
    }
}
