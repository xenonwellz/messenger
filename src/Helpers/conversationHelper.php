<?php

function getConversationText($conversation)
{
    $message = \Xenonwellz\Messenger\Models\Message::where('sender_id', $conversation->id)
        ->where('receiver_id', auth()->user()->id)
        ->whereNull('deleted_other_at')
        ->orWhere(function ($query) use (&$conversation) {
            $query
                ->where('receiver_id', $conversation->id)
                ->where('sender_id', auth()->user()->id)
                ->whereNull('deleted_sender_at');
        })
        ->orderBy('created_at', 'desc')
        ->get()
        ->first();
    if ($message) {
        if ($message->message) {
            if ($message->read_at == null && $message->sender_id != auth()->user()->id) {
                return '<b>' . $message->message . '</b>';
            } else {
                return $message->message;
            }
        } elseif ($message->attachment_path) {
            if (isWebImage($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-image"></i> Image Attachment</span>';
            } else if (isAudio($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-music"></i> Audio Attachment</span>';
            } else if (isVideo($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-play"></i> Video Attachment</span>';
            } else if (isPDF($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-pdf"></i> PDF Attachment</span>';
            } else if (isWordDocument($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-word"></i> Word Attachment</span>';
            } else if (isArchieve($message->attachment_path)) {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark-zip"></i> Archieve Attachment</span>';
            } else {
                return '<span class="font-bold pl-1"><i class="bi bi-file-earmark"></i> Unknown Attachment</span>';
            }
        }
    } else {
        return 'Start new conversation with ' . $conversation->name;
    }
}
