<?php

namespace App\Services\Notification;

use App\Enums\NotificationCatEnum;
use App\Models\Notification;

class PublicNotificationService extends BasicNotificationService
{
    /**
     * Display a listing of public notification added by administrators.
     *
     * @param $request
     */
    public function allNotifications($request)
    {
        $notifications = Notification::where('category', NotificationCatEnum::PUBLIC)->latest();
        $limit = 12;
        if ($request->filled('limit'))
            $limit = $request->limit;

        $page = 1;
        if ($request->filled('page'))
            $page = $request->page;

        $data = $notifications->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return ['statusCode' => 200, 'data' => $data, 'message' => 'Here are all public notifications!'];
    }

    /**
     * Remove the specified public notification from storage.
     *
     * @param  int  $notify_id
     * @return boolean
     */
    public function deleteNotification($notify_id)
    {
        if ($notify = Notification::where('category', NotificationCatEnum::PUBLIC)->where('id',$notify_id)->first()) {
            $notify->delete();
            return ['statusCode' => 200, 'data' => null, 'message' => 'Notification deleted successfully.'];
        } else return ['statusCode' => 404, 'data' => null, 'message' => 'Notification not found!'];
    }
}
