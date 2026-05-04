<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
   public function index(Request $request)
{
    $query = Lead::where('user_id', Auth::id());

    // 🔍 Search
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', '%'.$request->search.'%')
              ->orWhere('email', 'like', '%'.$request->search.'%')
              ->orWhere('phone', 'like', '%'.$request->search.'%');
        });
    }

    // 🎯 Filter by status
    if ($request->status) {
        $query->where('status', $request->status);
    }

    return $query->latest()->paginate(10);
}

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $lead = Lead::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'source' => $request->source,
            'status' => 'new'
        ]);

        return response()->json($lead, 201);
    }

    public function show($id)
    {
        $lead = Lead::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($lead);
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::where('user_id', Auth::id())->findOrFail($id);

        $lead->update($request->only(['name', 'email', 'phone', 'source', 'status']));

        return response()->json($lead);
    }

    public function destroy($id)
    {
        $lead = Lead::where('user_id', Auth::id())->findOrFail($id);
        $lead->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
