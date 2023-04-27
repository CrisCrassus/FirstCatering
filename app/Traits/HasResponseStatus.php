<?php

namespace App\Traits;

use App\Enums\ResponseStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;

trait HasResponseStatus
{
    public function responseStatus(ResponseStatus $status, string $message, $data = null)
    {
        $responseNumber = 0;

        if($status->value == ResponseStatus::SUCCESS->value) {
            $responseNumber = 200;
        }

        if($status->value == ResponseStatus::FAILED->value) {
            $responseNumber = 404;
        }

        if($status->value == ResponseStatus::ERROR->value) {
            $responseNumber = 500;
        }

        if($data) {
            if(is_array($data)) {

                $top = ['status' => $status, 'message' => $message,];
                $responseData = array_merge($top, $data);

                return response()->json($responseData, $responseNumber);
            }
            if(!is_array($data)) {
                return response()->json([
                    'status' => $status,
                    'message' => $message,
                    'data' => $data,
                ], $responseNumber);
            }
        } else {
            return response()->json([
                'status' => $status,
                'message' => $message,
            ], $responseNumber);
        }
    }
}
