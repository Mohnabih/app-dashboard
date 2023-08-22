<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\BaseRequest;

class StoreUserRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'email' => 'nullable|required_without:phoneNumber|email:filter|unique:users',
            'phoneNumber' => 'nullable|required_without:email|regex:/^([+]+[0-9\s\-\+\(\)]*)$/|min:9|max:15|unique:users,phoneNumber',
            'registerBy' => 'required|string|max:100',
            'password' => 'required|string|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%]).*$/|min:8|confirmed'
        ];
    }
}
