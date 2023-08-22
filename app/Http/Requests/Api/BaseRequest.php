<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Response;

class BaseRequest extends FormRequest
{

  /**
     * Set the exception to throw upon failed validation.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $response = Response::json([
            'status' => 400,
            'errorCode' => 1,
            'data' =>  $validator->errors(),
            'message' => __("validation error!")
        ], 400);
        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
