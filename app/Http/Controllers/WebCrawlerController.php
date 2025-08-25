<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\DomCrawler\Crawler;



class WebCrawlerController extends Controller
{
    public function index()
    {
        return view('crawler');
    }



    public function crawl(Request $request)
    {
        $url = $request->url;
        $prompt = $request->prompt;

        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        $html = $response->getContent();
        $crawler = new Crawler($html);

        $textData = $crawler->filter('h1, h2, h3, p, li')->each(function ($node) {
            return $node->text();
        });

        $websiteText = implode("\n", array_slice($textData, 0, 1000));

        // Send to OpenAI
        $aiResponse = Http::withToken(env('OPEN_AI_KEY'))->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful AI web content reader.'],
                ['role' => 'user', 'content' => "Here is a website:\n$websiteText\n\nUser Query: $prompt"]
            ]
        ]);

        return response()->json([
            'reply' => $aiResponse['choices'][0]['message']['content']
        ]);
    }
}
