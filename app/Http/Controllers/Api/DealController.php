<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DealController extends Controller
{
    public function index()
{
    return Deal::whereHas('lead', function ($q) {
        $q->where('user_id', Auth::id());
    })->with('lead')->latest()->get();
}
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
            'value' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Ensure lead belongs to logged-in user
        $lead = Lead::where('user_id', Auth::id())->findOrFail($request->lead_id);

        $deal = Deal::create([
            'lead_id' => $lead->id,
            'value' => $request->value,
            'stage' => 'prospect',
            'expected_close_date' => $request->expected_close_date
        ]);

        // Optional: update lead status
        $lead->update(['status' => 'qualified']);

        return response()->json($deal, 201);
    }

    public function show($id)
{
    return Deal::whereHas('lead', function ($q) {
        $q->where('user_id', Auth::id());
    })->with('lead')->findOrFail($id);
}

    public function update(Request $request, $id)
{
    $deal = Deal::whereHas('lead', function ($q) {
        $q->where('user_id', Auth::id());
    })->findOrFail($id);

    $deal->update($request->only(['value', 'stage', 'expected_close_date']));

    return response()->json($deal);
}

    public function destroy($id)
    {
        $deal = Deal::findOrFail($id);
        $deal->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
