<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Deal;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function stats()
    {
        $userId = Auth::id();

        return response()->json([
            'total_leads' => Lead::where('user_id', $userId)->count(),
            'converted_leads' => Lead::where('user_id', $userId)->where('status', 'converted')->count(),
            'total_deals' => Deal::whereHas('lead', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'won_deals' => Deal::where('stage', 'won')->whereHas('lead', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
        ]);
    }
}
