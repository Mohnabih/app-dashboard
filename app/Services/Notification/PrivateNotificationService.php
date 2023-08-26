<?php

namespace App\Services\Notification;

use App\Enums\NotificationCatEnum;
use App\Jobs\SendPrivateNotification;
use App\Jobs\SendPublicNotification;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class PrivateNotificationService extends BasicNotificationService
{
    /**
     * Display a listing of notification.
     *
     * @param $request
     */
    public function allNotifications($request)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->latest();
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
     * Remove the specified notification from storage.
     *
     * @param  int  $notify_id
     * @return boolean
     */
    public function deleteNotification($notify_id)
    {
        $user = Auth::user();
        if ($notify = Notification::find($notify_id)) {
            if ($user->notifications()->where('notification_id', $notify_id)->exists()) {
                if ($notify->category == NotificationCatEnum::PRIVATE)
                    $notify->delete();
                else
                    $user->notifications()->detach($notify_id);
                return ['statusCode' => 200, 'data' => null, 'message' => 'Notification deleted successfully.'];
            } else return ['statusCode' => 404, 'data' => null, 'message' => 'Notification not found!'];
        } else return ['statusCode' => 404, 'data' => null, 'message' => 'Notification not found!'];
    }
}
