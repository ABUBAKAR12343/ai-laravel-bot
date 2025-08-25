<?php

use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\emailGenrator;
use App\Http\Controllers\imageRecognizerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\WebCrawlerController;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/chat', [ChatBotController::class, 'index']);
Route::post('/chat/send', [ChatBotController::class, 'askAI']);


Route::get('/resume-parser', [ResumeController::class, 'index']);
Route::post('/resume-parser/upload', [ResumeController::class, 'upload']);


Route::get('/crawler', [WebCrawlerController::class, 'index']);
Route::post('/crawler', [WebCrawlerController::class, 'crawl']);


Route::view('/chat-ai', 'conversation');
Route::post('/send-message',[ConversationController::class,'chat']);

Route::get('/imageRecognizer',[imageRecognizerController::class,'index'])->name('imageRecognizer');
Route::post('/imageUploader',[imageRecognizerController::class,'imageUploader'])->name('imageUploader');


Route::get('/emailGenrator',[emailGenrator::class,'index'])->name('emailGenrator');
Route::post('/generateEmail',[emailGenrator::class,'generateEmail'])->name('generateEmail');


