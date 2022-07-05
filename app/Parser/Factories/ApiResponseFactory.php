<?php

namespace App\Parser\Factories;

class ApiResponseFactory
{
    public static function response($data = [], $errors, $current_page, $eof = false)
    {
        $data = [
            "data" => $data,
            "errors"=> $errors,
            "current_page" => $current_page,
            "eof" => $eof
        ];

        return response()->json($data);
    }

}
