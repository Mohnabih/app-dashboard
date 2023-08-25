<?php

namespace App\Http\Controllers\Api\Dashboard\v1;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Services\Note\AdminNoteService;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{
    private AdminNoteService $adminService;

    /**
     * Instantiate a new controller instance.
     *
     * @param \App\Services\Note\AdminNoteService $adminService
     * @return void
     */
    public function __construct(AdminNoteService $adminService)
    {
        $this->middleware('auth:api');
        $this->adminService = $adminService;
    }

    /**
     * Display a listing of clients.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function allClients(Request $request)
    {
        try {
            $data = $this->adminService->allClients($request);
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
     * Display a listing of clients notes.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function allNotes(Request $request)
    {
        try {
            $data = $this->adminService->allNotes($request);
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
