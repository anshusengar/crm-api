<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    public function index()
    {
        return Note::where('user_id', Auth::id())
    ->with(['lead', 'deal'])
    ->latest()
    ->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $note = Note::create([
            'user_id' => Auth::id(),
            'lead_id' => $request->lead_id,
            'deal_id' => $request->deal_id,
            'content' => $request->content,
        ]);

        return response()->json($note, 201);
    }

    public function show($id)
    {
        return Note::where('user_id', Auth::id())->findOrFail($id);
    }

    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
