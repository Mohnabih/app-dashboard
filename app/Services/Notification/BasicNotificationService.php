<?php

namespace App\Services\Notification;

use App\Enums\NotificationCatEnum;
use App\Jobs\SendPrivateNotification;
use App\Jobs\SendPublicNotification;
use App\Models\Notification;

class BasicNotificationService
{
    /**
     * Store a newly created public notification in storage.
     *
     * @param $request
     *
     * @return \App\Models\Notification $notification
     */
    public function createNotification($request)
    {
        $input = $request->only(['title', 'content']);
        if ($request->filled('user_id'))
            $input['category'] = NotificationCatEnum::PRIVATE;
        else
            $input['category'] = NotificationCatEnum::PUBLIC;

        // Create notification.
        $notify = Notification::create($input);

        if ($request->filled('user_id'))
            SendPrivateNotification::dispatch($notify->id, $request->user_id);
        else
            SendPublicNotification::dispatch($notify->id);
        return ['statusCode' => 201, 'data' => $notify, 'message' => 'Notification added successfully.'];
    }
}
