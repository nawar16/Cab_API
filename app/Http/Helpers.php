<?php
if (!function_exists('error_response')) {
	function error_response($ex)
    {
        $data = [
            'status' => false,
            'code' => 500,
            'err' => $ex->getMessage()
        ];
        return response()->json($data, 500);
    }
}
if (!function_exists('success_response')) {
	function success_response($data)
    {
        $res = [
            'status' => true,
            'code' => 200,
            'data' => $data,
            'err' => null
        ];
        return response()->json($res, 200);
    }
}