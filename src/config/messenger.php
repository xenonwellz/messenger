<?php

return [

    // Always add toQuery if using collections
    "allow_conversation_with" => \App\Models\User::all()->toQuery(),

    // Set to true to use avatar field in the user table
    "use_avatar_field" => false,

    //After changing avatar field name please run php artisan migrate:fresh
    "avatar_field_name" => 'avatar',

    // default avatar url (must be callable with Storage::get()) or an actual url Leave empty to use the uiavatars api
    "default_avatar" => '',

    // allowed mime types seperated by commas
    "allowed_mime_types" => 'png,jpg,gif,webm,mp4,mp3,wav,pdf,doc,docx,zip,mov',

    // Maximum file size in Kilobytes (must be less than php.ini specified)
    'max_file_size' => 1024,

    //max file to be sent at once
    'max_file_at_once' => 5,

    'file_storage_path' => "/message-attachment/",

    //use pusher or laravel websockets
    'websocket_provider' => "laravel-websockets",

    //Set to true to configure laravel echo to use ssl
    'force_websocket_ssl/tls' => false,

    'route_prefix' => 'messenger'


];
