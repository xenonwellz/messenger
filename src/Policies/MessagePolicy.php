<?php

namespace Xenonwellz\Messenger\Policies;

use App\Models\User;
use Xenonwellz\Messenger\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function message(User $auth, User $user)
    {
        $allowed = config('messenger.allow_conversation_with')
            ->where('id', '!=', $auth->id)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();
        return in_array($user->id, $allowed);
    }

    public function download(User $auth, $file)
    {
        $count = config('messenger.allow_conversation_with')
            ->where(function ($query) use (&$auth, &$file) {
                $query->where('attachment_path', '!=', $file)
                    ->where('sender_id', $auth->id);
            })
            ->orWhere(function ($query) use (&$auth, &$file) {
                $query->where('attachment_path', '!=', $file)
                    ->where('receiver_id', $auth->id);
            })
            ->count();
        return $count > 0;
    }
}
