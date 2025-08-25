<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConversationController extends Controller
{

    public function chat(Request $request)
    {

        if (!session()->has('chat_history')) {
            session(['chat_history' => []]);
        }

        $history = session('chat_history');

        $history[] = [
            'role' => 'user',
            'content' => $request->message
        ];

        array_unshift($history, [
            'role' => 'system',
            'content' => 'You are a chatbot assistant. Answer user questions based on chat history.'
        ]);


        $response =  Http::withToken(env('OPEN_AI_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => $history
        ]);

        $aiReply = $response['choices'][0]['message']['content'];

        $history[] = [
            'role' => 'assistant',
            'content' => $aiReply
        ];

        session(['chat_history' => $history]);

        return response()->json(['reply' => $aiReply]);
    }
}
