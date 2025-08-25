<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;

class ResumeController extends Controller
{
    public function index()
    {
        return view('resume');
    }

    public function upload(Request $request)
    {
        // $request->validate([
        //     'resume' => 'required',
        // ]);

        $files = $request->file('resume');
        $results = [];

        $customInstruction = $request->input('requirements');

        $prompt = '
You are a professional HR Manager.:

User Data is:
' . $customInstruction . '

---
IF USER REQUIREMENTS ARE ABOUT RESUME OR RECURIMENT ONLY THEN SHOW RESULT OTHER WISE TELL HIM YOU ARE NOT ALLOWED TO TALK TO ANY OTHER THING...
---
IF REQUIREMENTS ARE FOR RESUME PARSING THEN

You have to get user requirements and tell how much good this resume is **According to USER Requirements". Rate resume over 10.

✅ Format your response like this:
---
Candidate Name: [Extracted Name]
Skills: [Comma separated]
University: [Extracted if available]
CGPA: [Extracted if available]
Contact Number: [Extracted if available]
✅ Shortlisted: Yes/No
Reason: [Why shortlisted or not]
Rate: [number over 10: example 4/10 (based on resume]


⚠️ Do not answer anything outside this task.
';


        $parser = new Parser();

        foreach ($files as $file) {
            $storagePath = public_path('app/storage');
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0755, true);
            }

            $uniqueName = time() . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $filePath = $storagePath . '/' . $uniqueName;
            $file->move($storagePath, $uniqueName);

            $extracted = $parser->parseFile($filePath);
            $resumeText = $extracted->getText();

            $response = Http::withToken(env('OPEN_AI_KEY'))->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $prompt
                    ],
                    [
                        'role' => 'user',
                        'content' => $resumeText
                    ]
                ]
            ]);

            $reply = $response['choices'][0]['message']['content'];
            $results[] = [
                'file' => $file->getClientOriginalName(),
                'response' => $reply
            ];
        }

        return view('resume', compact('results'));
    }
}
