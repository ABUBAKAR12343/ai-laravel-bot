<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class emailGenrator extends Controller
{
    public function index()
    {
        return view('email_generator');
    }

    public function generateEmail(Request $request)
    {

        $request->validate([
            'letter_type' => 'required|string|max:255',
            'details' => 'required|string',
        ]);


        $systemPrompt = "You are an AI email generator. Generate a professional email based on the following details:\n\n";
        $systemPrompt .= "Letter Type: " . $request->input('letter_type') . "\n";
        $systemPrompt .= "Details: " . $request->input('details') . "\n\n";
        $systemPrompt .= "Please generate a well-structured email.";

        $response =  Http::withToken(env('OPEN_AI_KEY'))
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $request->input('details')],
                ],
            ]);

        if ($response->successful()) {
            $data = $response->json();
            $generatedText = $data['choices'][0]['message']['content'] ?? 'No content generated.';

            return view('email_generator', ['generatedEmail' => $generatedText]);
            // dd($generatedText);
        }

        return back()->with('error', 'Failed to generate email. Please try again later.');

        // For now, we'll just return a success message
        return redirect()->back()->with('generatedEmail', 'Email generated successfully!');
    }
}
