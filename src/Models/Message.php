<?php

namespace Xenonwellz\Messenger\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message',
        'attachment_path'
    ];

    public function newFactory()
    {
        return \Xenonwellz\Messenger\Database\Factories\MessageFactory::new();
    }
}
