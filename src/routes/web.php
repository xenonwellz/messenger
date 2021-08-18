<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Xenonwellz\Messenger\Http\Controllers\MessageController;


Route::prefix('messenger')->middleware('web', 'auth')->group(function () {
    //

    Route::get('/', function () {
        return view('messenger::messenger');
    });
    Route::post('/search-conversations', [MessageController::class, 'search']);
    Route::post('/get-conversation', [MessageController::class, 'get']);
    Route::post('/messages', [MessageController::class, 'index']);
    Route::post('/messages-nav',  [MessageController::class, 'nav']);
    Route::post('/messages-about',  [MessageController::class, 'about']);
    Route::post('/send',  [MessageController::class, 'send']);
    Route::post('/send-files',  [MessageController::class, 'sendFile']);
    Route::post('/online-users',  [MessageController::class, 'online']);
    Route::post('/get-last',  [MessageController::class, 'getLast']);

    Route::delete('/all/{id}',  [MessageController::class, 'deleteAll']);

    Route::get('/download/{url}', [MessageController::class, 'download'])->where(['url' => "[\w\/ . -]+"]);
    //
});