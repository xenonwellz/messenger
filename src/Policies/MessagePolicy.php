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
}
