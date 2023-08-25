<?php

namespace App\Http\Requests\Api\Note;

use App\Http\Requests\Api\BaseRequest;

class StoreNoteRequest extends BaseRequest
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
            'subject'=>'required|string|max:100',
            'body'=>'required_without:images|string',
            'images.*'=>'nullable|file|image'
        ];
    }
}
