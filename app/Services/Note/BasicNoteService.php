<?php

namespace App\Services\Note;

use App\Enums\UserRoleEnum;
use App\Models\Media;
use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BasicNoteService
{
    /**
     * Display a listing of public notes added by administrators.
     *
     * @param $request
     */
    public function publicNotes($request)
    {
        $admins_ids = User::where('role', UserRoleEnum::ADMIN)->pluck('id')->toArray();
        $notes = Note::whereIn('user_id', $admins_ids)->when($request->filled('search'), function ($query) use ($request) {
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

    /**
     * Display a listing of authenticated user notes.
     *
     * @param $request
     */
    public function UserNotes($request)
    {
        $user = Auth::user();
        $notes = $user->notes()->latest();

        $limit = 12;
        if ($request->filled('limit'))
            $limit = $request->limit;

        $page = 1;
        if ($request->filled('page'))
            $page = $request->page;

        $data = $notes->paginate($limit)->toArray();
        $data['items'] =   $data['data'];
        unset($data['data']);
        return ['statusCode' => 200, 'data' => $data, 'message' => 'Here are all notes!'];
    }

    /**
     * Store a newly created note in storage.
     *
     * @param $request
     *
     * @return \App\Models\NoteNote $note
     */
    public function createNote($request)
    {
        $user = Auth::user();
        $input = $request->only(['subject', 'body']);
        $note = $user->notes()->create($input);
        if ($request->has('images')) {
            foreach ($request->file('images') as  $file) {
                $uuid = Str::uuid();
                $note->addMedia($file)->usingName($uuid)->usingFileName($uuid . '.' . $file->getClientOriginalExtension())->toMediaCollection('images');
            }
        }
        return ['statusCode' => 201, 'data' => Note::find($note->id), 'message' => 'Note created successfully.'];
    }

    /**
     * Display the specified note.
     *
     * @param  int  $note_id
     * @return \App\Models\NoteNote $not
     */
    public function showNote($note_id)
    {
        $user = Auth::user();
        if ($note = Note::find($note_id)) {
            if ($note->user_id == $user->id || $user->role === UserRoleEnum::ADMIN)
                return ['statusCode' => 200, 'data' => $note, 'message' => 'Here is note!'];
            else return ['statusCode' => 403, 'data' => null, 'message' => null];
        } else return ['statusCode' => 404, 'data' => $note, 'message' => 'Note not found!'];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $request
     * @param  int  $note_id
     * @return \App\Models\NoteNote $not
     */
    public function updateNote($request, $note_id)
    {
        $user = Auth::user();
        $input = $request->only(['subject', 'body']);
        if ($note = Note::find($note_id)) {
            if ($note->user_id == $user->id) {
                $note->update($input);
                if ($request->has('images')) {
                    foreach ($request->file('images') as  $file) {
                        $uuid = Str::uuid();
                        $note->addMedia($file)->usingName($uuid)->usingFileName($uuid . '.' . $file->getClientOriginalExtension())->toMediaCollection('images');
                    }
                }
                // Delete images.
                if ($request->filled('images_delete')) {
                    foreach ($request->images_delete as $media_id) {
                        if ($media = $note->media()->find($media_id))
                            $media->delete();
                    }
                }
                return ['statusCode' => 200, 'data' => Note::find($note->id), 'message' => 'Note updated successfully'];
            } else return ['statusCode' => 403, 'data' => null, 'message' => null];
        } else return ['statusCode' => 404, 'data' => $note, 'message' => 'Note not found!'];
    }

    /**
     * Remove the specified note from storage.
     *
     * @param  int  $note_id
     * @return boolean
     */
    public function deleteNote($note_id)
    {
        $user = Auth::user();
        if ($note = Note::find($note_id)) {
            if ($note->user_id === $user->id || $user->role == UserRoleEnum::ADMIN) {
                $note->delete();
                return ['statusCode' => 200, 'data' => null, 'message' => 'Note deleted successfully'];
            } else return ['statusCode' => 403, 'data' => null, 'message' => null];
        } else return ['statusCode' => 404, 'data' => $note, 'message' => 'Note not found!'];
    }
}
