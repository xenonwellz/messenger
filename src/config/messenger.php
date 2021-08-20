<?php

return [

    // Always add toQuery if using collections
    "allow_conversation_with" => \App\Models\User::all()->toQuery(),

    "use_avatar_field" => true,
    "avatar_field_name" => 'avatar',

    // default avatar url (must be in storage)
    "default_avatar" => 'http://localhost/index.jpg',

    // allowed mime types seperated by commas
    "allowed_mime_types" => 'png,jpg,gif,webm,mp4,mp3,wav,pdf,doc,docx,zip,mov',

    // Maximum file size in Kilobytes (must be less than php.ini specified)
    'max_file_size' => 1024,

    'max_file_at_once' => 5,
    'file_storage_path' => "/message-attachment/"


];
