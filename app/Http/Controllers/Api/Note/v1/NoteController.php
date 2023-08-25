<?php

namespace App\Http\Controllers\Api\Note\v1;

use App\ApiCode;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\Api\Note\StoreNoteRequest;
use App\Http\Requests\Api\Note\UpdateNoteRequest;
use App\Services\Note\BasicNoteService;
use Illuminate\Http\Request;

class NoteController extends AppBaseController
{
    private BasicNoteService $noteService;

    /**
     * Instantiate a new controller instance.
     *
     * @param \App\Services\Note\BasicNoteService $noteService
     * @return void
     */
    public function __construct(BasicNoteService $noteService)
    {
        $this->middleware('auth:api');
        $this->noteService = $noteService;
    }

    /**
     * Display a listing of public notes added by administrators.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $data = $this->noteService->publicNotes($request);
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
     * Display a listing of authenticated user notes.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function userNotes(Request $request)
    {
        try {
            $data = $this->noteService->UserNotes($request);
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Api\Note\StoreNoteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoteRequest $request)
    {
        try {
            $data = $this->noteService->createNote($request);
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
     * Display the specified note.
     *
     * @param  int  $note_id
     * @return \Illuminate\Http\Response
     */
    public function show($note_id)
    {
        try {
            $data = $this->noteService->showNote($note_id);
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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Api\Note\UpdateNoteRequest  $request
     * @param  int  $note_id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNoteRequest $request, $note_id)
    {
        try {
            $data = $this->noteService->updateNote($request, $note_id);
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
     * Remove the specified note from storage.
     *
     * @param  int  $note_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($note_id)
    {
        try {
            $data = $this->noteService->deleteNote($note_id);
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
