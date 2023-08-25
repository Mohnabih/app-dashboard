<?php

namespace App\Services\Note;

use App\Enums\UserRoleEnum;
use App\Models\Note;
use App\Models\User;

class AdminNoteService
{
    /**
     * Display a listing of clients.
     *
     * @param $request
     */
    public function allClients($request)
    {
        $clients = User::where('role', UserRoleEnum::CLIENT)->latest();

        $limit = 12;
        if ($request->filled('limit'))
            $limit = $request->limit;

        $page = 1;
        if ($request->filled('page'))
            $page = $request->page;

        $data = $clients->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return ['statusCode' => 200, 'data' => $data, 'message' => 'Here are all clients!'];
    }

    /**
     * Display a listing of clients notes.
     *
     * @param $request
     */
    public function allNotes($request)
    {
        $admins_ids = User::where('role', UserRoleEnum::ADMIN)->pluck('id')->toArray();
        $notes = Note::whereNotIn('user_id', $admins_ids)->when($request->filled('search'), function ($query) use ($request) {
            $query->where('subject', 'LIKE', "%{$request->search}%")
                ->orWhere('body', 'LIKE', "%{$request->search}%");
        })->latest();

        $limit = 12;
        if ($request->filled('limit'))
            $limit = $request->limit;

        $page = 1;
        if ($request->filled('page'))
            $page = $request->page;

        $data = $notes->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return ['statusCode' => 200, 'data' => $data, 'message' => 'Here are all public notes!'];
    }
}
