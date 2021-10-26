<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    protected function generateStatusMessage($status) {
        $message = 'Bad Request';
        switch($status) {
            case 200:
                $message = 'OK';
                break;
            case 201:
                $message = 'Created';
                break;
            case 202:
                $message = 'Accepted';
                break;
            case 203:
                $message = 'Non-Authoritative Information';
                break;
            case 204:
                $message = 'No Content';
                break;
            case 205:
                $message = 'Reset Content';
                break;
            case 206:
                $message = 'Partial Content';
                break;
            case 300:
                $message = 'Multiple Choices';
                break;
            case 301:
                $message = 'Moved Permanently';
                break;
            case 302:
                $message = 'Found';
                break;
            case 303:
                $message = 'See Other';
                break;
            case 304:
                $message = 'Not Modified';
                break;
            case 305:
                $message = 'Use Proxy';
                break;
            case 306:
                $message = '(Unused)';
                break;
            case 307:
                $message = 'Temporary Redirect';
                break;
            case 400:
                $message = 'Bad Request';
                break;
            case 401:
                $message = 'Unauthorized';
                break;
            case 402:
                $message = 'Payment Required';
                break;
            case 403:
                $message = 'Forbidden';
                break;
            case 404:
                $message = 'Not Found';
                break;
            case 405:
                $message = 'Method Not Allowed';
                break;
            case 406:
                $message = 'Not Acceptable';
                break;
            case 407:
                $message = 'Proxy Authentication Required';
                break;
            case 408:
                $message = 'Request Timeout';
                break;
            case 409:
                $message = 'Conflict';
                break;
            case 410:
                $message = 'Gone';
                break;
            case 411:
                $message = 'Length Required';
                break;
            case 412:
                $message = 'Precondition Failed';
                break;
            case 413:
                $message = 'Request Entity Too Large';
                break;
            case 414:
                $message = 'Request-URI Too Long';
                break;
            case 415:
                $message = 'Unsupported Media Type';
                break;
            case 416:
                $message = 'Requested Range Not Satisfiable';
                break;
            case 417:
                $message = 'Expectation Failed';
                break;
            case 500:
                $message = 'Internal Server Error';
                break;
            case 501:
                $message = 'Not Implemented';
                break;
            case 502:
                $message = 'Bad Gateway';
                break;
            case 503:
                $message = 'Service Unavailable';
                break;
            case 504:
                $message = 'Gateway Timeout';
                break;
            case 505:
                $message = 'HTTP Version Not Supported';
                break;
        }

        return $message;
    }
    public function exceptionAPIError(\Exception $exception) {
        return array(
            'exception' => array(
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'code' => $exception->getCode(),
                'message' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString()
            )
        );
    }
    protected function error_response($ex)
    {
        $data = [
            'status' => false,
            'code' => 500,
            'err' => $this->generateStatusMessage($ex->getCode())
        ];
        return response()->json($data, 500);
    }
    protected function success_response($data)
    {
        $res = [
            'status' => true,
            'code' => 200,
            'data' => $data,
            'err' => null
        ];
        return response()->json($res, 200);
    }
    protected function upload_image($model = 'clients', $img = null)
    {
        $image = $img ?? request('image') ;
        if ($image) {
            $extension = $image->getClientOriginalExtension();
            $filenametostore = $model . '_' . time() . '.' . $extension;
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0755);
            }
            if (!file_exists(public_path('uploads/' . $model))) {
                mkdir(public_path('uploads/' . $model), 0755);
            }
            $img = \Image::make($image)->save(public_path('uploads/' . $model . '/' . $filenametostore));
            if($img){
                return $filenametostore;
            }
        }
        return false;
    }
}
