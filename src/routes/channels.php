<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('message', function ($user) {
    return $user;
});

Broadcast::channel('online', function ($user) {
    return $user;
});
