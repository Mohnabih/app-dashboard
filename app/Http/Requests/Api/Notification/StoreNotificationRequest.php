<?php

namespace App\Http\Requests\Api\Notification;

use App\Http\Requests\Api\BaseRequest;

class StoreNotificationRequest extends BaseRequest
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
            'user_id' => 'nullable|exists:users,id',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string'
        ];
    }
}
