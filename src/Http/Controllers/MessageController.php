<?php

namespace Xenonwellz\Messenger\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;

use Xenonwellz\Messenger\Events\ReadMessage;
use Illuminate\Http\Request;
use Xenonwellz\Messenger\Events\DeliveredMessage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Xenonwellz\Messenger\Models\Message;
use Xenonwellz\Messenger\Events\NewMessage;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user' => 'required|integer',
            'offset' => 'integer',
            'limit' => 'required|integer',
            'tz' => 'required|integer'
        ]);

        $user = User::find($request->user);
        if (!Gate::check('message', $user)) {
            return view('messenger::components.structure.new-conv')->with([
                'user' => $user,
                'message' => 'You are not allowed to message '
            ]);
        }

        $messages = Message::where('sender_id', $request->user)
            ->where('receiver_id', auth()->user()->id)
            ->whereNull('deleted_other_at')
            ->orWhere(function ($query) use (&$request) {
                $query->where('receiver_id',  $request->user)
                    ->where('sender_id', auth()->user()->id)
                    ->whereNull('deleted_sender_at');
            });
        $messages = $messages
            ->orderBy('created_at', 'desc')
            ->skip($request->offset)
            ->take($request->limit)
            ->get()
            ->reverse();

        if ($messages->count() < 1 && $request->offset < 1) {
            return view('messenger::components.structure.new-conv')->with([
                'user' => User::find($request->user),
                'message' => 'Start new conversation with '
            ]);
        } else if ($messages->count() < 1) {
            return 0;
        }

        $this->markAsRead($request->user);

        return view('messenger::components.message.message')->with([
            'messages' => $messages,
            'tz' => $request->tz
        ]);
    }

    public function nav(Request $request)
    {
        $request->validate([
            'user' => 'integer'
        ]);

        $user = User::find($request->user);

        return view('messenger::components.message.nav-user')->with([
            'user' => $user
        ]);
    }

    public function about(Request $request)
    {
        $request->validate([
            'user' => 'integer'
        ]);

        $user = User::find($request->user);

        return view('messenger::components.about.main')->with([
            'user' => $user
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'val' => 'string',
            'limit' => 'integer'
        ]);

        $query = config('messenger.allow_conversation_with')
            ->where('name', 'like',  $request->val . '%')
            ->where('id', '!=', auth()->user()->id)
            ->orderBy('name')
            ->limit($request->limit)
            ->get();

        if ($query->count() == 0) {
            return "<div class='text-center text-gray-700 text-sm font-bold'>No records Found</div>";
        } elseif ($query->count() < $request->limit && $request->limit != 10) {
            return "0";
        }

        return view('messenger::components.structure.conversation')->with([
            'users' => $query
        ]);
    }

    public function get(Request $request)
    {
        $request->validate([
            'limit' => 'integer'
        ]);

        $users = Message::join('users', function ($join) {
            $join->on('users.id', '=', 'messages.receiver_id')
                ->where('messages.sender_id', auth()->user()->id)
                ->whereNull('messages.deleted_sender_at')
                ->orOn(function ($query) {
                    $query->on('users.id', 'messages.sender_id')
                        ->where('messages.receiver_id', auth()->user()->id)
                        ->whereNull('messages.deleted_other_at');
                });
        })
            ->orderBy('messages.created_at', 'desc')
            ->groupBy('users.id')
            ->get();

        if ($users->count() == 0) {
            return '<div class="text-sm text-gray-700 font-bold text-center">Search user to begin conversation</div>';
        } elseif ($users->count() < $request->limit && $request->limit != 10) {
            return "0";
        }

        $this->markAsDelivered();
        $this->updateOnline(auth()->user()->id, 1);

        return view('messenger::components.structure.conversation')->with([
            'users' => $users
        ]);
    }

    public function markAsRead($user_id)
    {
        Message::where('sender_id', $user_id)
            ->where('receiver_id', auth()->user()->id)
            ->where('read_at', null)
            ->update(['read_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        broadcast(new ReadMessage(User::find($user_id)))->toOthers();
    }

    public function markAsDelivered()
    {
        Message::where('receiver_id', auth()->user()->id)
            ->where('delivered_at', null)
            ->update(['delivered_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        broadcast(new DeliveredMessage(User::find(auth()->user()->id)))->toOthers();
    }

    public function send(Request $request)
    {
        $request->validate([
            'text' => 'required|string',
            'to' => 'required|integer'
        ]);

        $to = User::find($request->to);

        Gate::authorize('message', $to);

        $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->to,
            'message' => $request->text
        ]);

        if ($to->online) {
            $message->delivered_at = Carbon::now()->format('Y-m-d H:i:s');
            $message->save();
        }
        broadcast(new NewMessage($to))->toOthers();
    }

    public function online()
    {
        $users = config('messenger.allow_conversation_with')
            ->where('id', '!=', auth()->user()->id)
            ->where('online', '1')
            ->limit(25)
            ->get();

        if ($users) {
            return view('messenger::components.structure.online-users')->with([
                'users' => $users
            ]);
        }

        return '<span class="flex items-center justify-center w-full">No online users.</span>';
    }

    public function deleteAll($user)
    {
        Gate::authorize('message', User::find($user));

        Message::where('sender_id', $user)
            ->where('receiver_id', auth()->user()->id)
            ->update(['deleted_other_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        Message::where('receiver_id',  $user)
            ->where('sender_id', auth()->user()->id)
            ->update(['deleted_sender_at' => Carbon::now()->format('Y-m-d H:i:s')]);

        return 'done';
    }

    public function getLast(Request $request)
    {
        $request->validate([
            'tz' => 'required|integer'
        ]);

        $message = Message::where('sender_id', auth()->user()->id)->orderBy('created_at', 'desc')->first();
        return view('messenger::components.message.sent')->with([
            'message' => $message,
            'tz' => $request->tz
        ]);
    }

    public function sendFile(Request $request)
    {
        $request->validate([
            'files' => 'required|file|mimes:' . config('messenger.allowed_mime_types') . '|max:' . config('messenger.max_file_size'),
            'to' => 'required|integer'
        ], [
            'mimes' => 'The uploaded file "' . $request->file('files')->getClientOriginalName() . '" is invalid.',
        ]);

        $to = User::find($request->to);

        Gate::authorize('message', $to);
        $file = $request->file('files');
        $name = md5($file->getClientOriginalName() . time()) . '.' . $file->extension();

        Storage::put('public' . config('messenger.file_storage_path') . $name, file_get_contents($file), 'public');

        $message = Message::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $request->to,
            'attachment_path' => config('messenger.file_storage_path') . $name
        ]);

        if ($to->online) {
            $message->delivered_at = Carbon::now()->format('Y-m-d H:i:s');
            $message->save();
        }

        broadcast(new NewMessage($to))->toOthers();
        return 'File sent successfully';
    }

    public function download($url)
    {
        $file = "/" . $url;
        $auth = auth()->user();
        $count = Message::where(function ($query) use (&$auth, &$file) {
            $query->where('attachment_path', $file)
                ->where('sender_id', $auth->id);
        })
            ->orWhere(function ($query) use (&$auth, &$file) {
                $query->where('attachment_path', $file)
                    ->where('receiver_id', $auth->id);
            })
            ->count();
        if ($count == 0) {
            return abort(403);
        }

        if (!Storage::exists('public' . '/' . $url)) {
            return abort(404);
        }
        return Storage::download('public' . '/' . $url);
    }
    public function unsend($id)
    {
        $message = Message::find($id);
        if ($message->sender_id == auth()->user()->id) {
            $message->deleted_sender_at = Carbon::now()->format('Y-m-d H:i:s');
            $message->deleted_other_at = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            return abort(403);
        }
        return $message->save();
    }

    public function delete($id)
    {
        $message = Message::find($id);
        if ($message->sender_id == auth()->user()->id) {
            $message->deleted_sender_at = Carbon::now()->format('Y-m-d H:i:s');
        } else {
            $message->deleted_other_at = Carbon::now()->format('Y-m-d H:i:s');
        }
        return $message->save();
    }

    public function updateOnline($id, $status)
    {
        $user = User::find($id);
        if ($status == 1) {
            $user->online = 1;
        } else if ($status == 0) {
            $user->online = null;
        } else {
            return abort(404);
        }
        $user->save();
        return $user;
    }

    public function received(Request $request)
    {
        $request->validate([
            'tz' => 'required|integer',
            'id' => 'required'
        ]);

        $messages = Message::where('sender_id', $request->id)->orderBy('created_at', 'desc')->first();

        $messages->delivered_at = Carbon::now()->format('Y-m-d H:i:s');
        $messages->read_at = Carbon::now()->format('Y-m-d H:i:s');

        $messages->save();

        broadcast(new ReadMessage(User::find($messages->receiver_id)))->toOthers();

        return view('messenger::components.message.received')->with([
            'message' => $messages,
            'tz' => $request->tz
        ]);
    }

    public function friend(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);

        return Gate::check('message', User::find($request->id));
    }
}
