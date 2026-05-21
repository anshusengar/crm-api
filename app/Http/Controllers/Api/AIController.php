<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use OpenAI;
use App\Models\AiLog;
use Illuminate\Support\Facades\Auth;

class AIController extends Controller
{
  public function summarizeNote(Request $request)
{
    $request->validate([
        'content' => 'required|string'
    ]);

    // 🔥 STATIC RESPONSE (for demo)
    $result = "Client is interested, requested discount, and wants a demo next week";

    \App\Models\AiLog::create([
        'user_id' => \Illuminate\Support\Facades\Auth::id() ?? 1,
        'prompt' => $request->content,
        'response' => $result
    ]);

    return response()->json([
        'summary' => $result
    ]);
}
   public function suggestAction(Request $request)
{
    $client = OpenAI::client(env('OPENAI_API_KEY'));

    $prompt = "Based on this CRM note, suggest next sales action:\n".$request->content;

    $response = $client->chat()->create([
        'model' => 'gpt-4.1-mini',
        'messages' => [
            ['role' => 'user', 'content' => $prompt],
        ],
    ]);

    $result = $response->choices[0]->message->content;

    // ✅ SAVE AI LOG HERE
    AiLog::create([
        'user_id' => Auth::id(),
        'prompt' => $request->content,
        'response' => $result
    ]);

    return response()->json([
        'suggestion' => $result
    ]);
}
}