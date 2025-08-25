<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function index()
    {
        return view('chat');
    }



    public function askAI(Request $request)
    {

$content = '
You are an HRMS assistant.

If the user says anything about applying leave, do the following:

👉 STEP 1: Try to detect if the user provided:
- User ID (like ExD-799)
- Leave type (e.g. Casual, Sick)
- Date or date range (e.g. till 10 July)
- Reason (like trip, fever, etc)

👉 STEP 2:
If ALL required fields are present, return ONLY the following JSON:

{
  "UserID": "[UserID]",
  "action": "apply_leave",
  "type": "[LeaveType]",
  "date": "[YYYY-MM-DD OR date range]",
  "reason": "[Leave Reason]"
}

✅ Example:
{
  "UserID": "ExD-813",
  "action": "apply_leave",
  "type": "Casual",
  "date": "'.Carbon::now().'",
  "reason": "Trip"
}

⚠️ IF anything is missing, ask politely: e.g. "Please provide your leave reason", or "What is your User ID?
If user have already applied leave between dates he mentioning "

DO NOT answer anything outside HRMS topics.
DO NOT explain what leave is. Just return JSON or ask missing info.
Today\'s date: ' . Carbon::today('Asia/Karachi')->toDateString() . '
';


        $userMessage = $request->message;

        $response = Http::withToken(env('OPEN_AI_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $content
                ],
                [
                    'role' => 'user',
                    'content' => $userMessage
                ]
            ]
        ]);


        $reply = $response['choices'][0]['message']['content'];

        $json = json_decode($reply, true);
        // dd($json);
        if ($json && isset($json['action']) && $json['action'] === 'apply_leave') {
            // Insert into DB
            Leave::create([
                'user_id' => $json['UserID'] ?? 'unknown',
                'leave_type' => $json['type'],
                'leave_date' => $json['date'],
                'reason' => $json['reason'],
                'status' => 'Pending'
            ]);

            return response()->json(['reply' => '✅ Your leave has been applied and saved in database!']);
        }

        return response()->json(['reply' => $reply]);
    }
}
