<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class imageRecognizerController extends Controller
{
    public function index()
    {
        return view('imageRecognizer');
    }

    public function imageUploader(Request $request)
{
    $request->validate([
        'images' => 'required',
        'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $matchingImages = [];

    foreach ($request->file('images') as $image) {
        // Store and read image
        $imagePath = $image->store('images', 'public');
        $fullPath = storage_path('app/public/' . $imagePath);
        $imageData = base64_encode(file_get_contents($fullPath));

        // Send image to OpenAI
        $response =  Http::withToken(env('OPEN_AI_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Does this image contain a clear cricket bat? Reply with Yes or No only.'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => 'data:image/jpeg;base64,' . $imageData,
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 20,
        ]);

        $aiReply = strtolower($response['choices'][0]['message']['content'] ?? '');

        // Check if AI said "yes"
        if (str_contains($aiReply, 'yes')) {
            $matchingImages[] = asset('storage/' . $imagePath);
        }
    }

    return view('imageRecognizer', compact('matchingImages'));
}

}
