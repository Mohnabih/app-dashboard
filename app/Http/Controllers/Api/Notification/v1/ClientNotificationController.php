<?php

namespace App\Http\Controllers\Api\Notification\v1;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Services\Notification\PrivateNotificationService;
use Illuminate\Http\Request;

class ClientNotificationController extends AppBaseController
{
    private PrivateNotificationService $notifyService;
    /**
     * Instantiate a new controller instance.
     *
     * @param \App\Services\Notification\PrivateNotificationService $notifyService
     * @return void
     */
    public function __construct(PrivateNotificationService $notifyService)
    {
        $this->middleware('auth:api');
        $this->notifyService = $notifyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->notifyService->allNotifications($request);
            return $this->handleResponse($data['statusCode'], $data['data'], $data['message']);
        } catch (\Exception $e) {
            return $this->sendResponse(
                null,
                __('Server Error'),
                ApiCode::INTERNAL_SERVER_ERROR,
                1
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $notify_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($notify_id)
    {
        try {
            $data = $this->notifyService->deleteNotification($notify_id);
            return $this->handleResponse($data['statusCode'], $data['data'], $data['message']);
        } catch (\Exception $e) {
            return $this->sendResponse(
                null,
                __('Server Error'),
                ApiCode::INTERNAL_SERVER_ERROR,
                1
            );
        }
    }
}
