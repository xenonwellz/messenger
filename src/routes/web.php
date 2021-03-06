<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use Xenonwellz\Messenger\Http\Controllers\MessageController;

Route::get('/', function () {
    return view('messenger::messenger');
});
Route::get('/download/{url}', [MessageController::class, 'download'])->where(['url' => "[\w\/ . -]+"]);

Route::post('/search-conversations', [MessageController::class, 'search']);
Route::post('/get-conversation', [MessageController::class, 'get']);
Route::post('/messages', [MessageController::class, 'index']);
Route::post('/get-last-received', [MessageController::class, 'received']);
Route::post('/messages-nav',  [MessageController::class, 'nav']);
Route::post('/messages-about',  [MessageController::class, 'about']);
Route::post('/send',  [MessageController::class, 'send']);
Route::post('/send-files',  [MessageController::class, 'sendFile']);
Route::post('/online-users',  [MessageController::class, 'online']);
Route::post('/get-last',  [MessageController::class, 'getLast']);
Route::post('/friend',  [MessageController::class, 'friend']);

Route::put('/user/{id}/{status}', [MessageController::class, 'updateOnline']);

Route::delete('/messages/{id}', [MessageController::class, 'delete']);
Route::delete('/messages/unsend/{id}', [MessageController::class, 'unsend']);
Route::delete('/all/{id}',  [MessageController::class, 'deleteAll']);
