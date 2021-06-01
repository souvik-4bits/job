<?php

namespace App\Helpers;

class APIHelpers
{
    public static function createAPIResponse($is_error, $message, $content)
    {
        $result = [];
        if ($is_error) {
            dd($is_error);
            $result['status'] = false;
            $result['message'] = $message;
        } else {
            $result['status'] = true;
            if ($content == null) {
                $result['message'] = $message;
            } else {
                $result['message'] = $message;
                $result['data'] = $content;
            }
        }
        return $result;
    }


    public static function ValidationApiResponse($is_error, $message, $content)
    {
        $result = [];
        $result['status'] = false;
        $result['message'] = $message;
        $result['data'] = $content;
        return $result;
    }
    public static function ApiErrorResponse($is_error, $message, $content)
    {
        $result = [];
        $result['status'] = false;
        $result['message'] = $message;
        $result['data'] = $content;
        return $result;
    }
}
