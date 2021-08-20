@foreach ($users as $conversation)
  <div class="bg-gray-200 rounded-md w-full flex mb-2 cursor-pointer" id="conversation"
    onclick="showMessageContainer({{ $conversation->id }})">
    <div class=" relative flex items-center justify-center flex-shrink-0">
      <img src="
    @if (config('messenger.use_avatar_field') &&
        $conversation[config('messenger.avatar_field_name')]) {{ $conversation[config('messenger.avatar_field_name')] }}
@elseif (config('messenger.default_avatar'))
                                           @if (asset(config('messenger.default_avatar')))
      {{ asset(config('messenger.default_avatar')) }}
    @else {{ config('messenger.default_avatar') }} @endif
    @else
      https://ui-avatars.com/api/?background=random&name={{ str_replace(' ', '+', trim($conversation->name)) }}
      @endif " alt="
      {{ $conversation->name }}"

      class="rounded-full h-16 w-16 truncate p-2">
      @if ($conversation->online)
        <span class="rounded-full absolute bg-green-500 h-auto border-gray-200 border-4 box-content bottom-2 right-2"
          style="padding: 6px"></span>
      @endif
    </div>
    <div class="flex-grow overflow-hidden md:col-span-9 py-3">
      <b class="block h-4 truncate mb-2 text-sm">{{ $conversation->name }}</b>
      <p class="text-xs grid grid-cols-12">
      <p class="text-xs text-green-500 hidden py-1" id="conversation-typing-{{ $conversation->id }}"><b>Typing...</b>
      </p>
      <span class="col-span-10 h-4 truncate"
        id="conversation-text-{{ $conversation->id }}">{!! getConversationText($conversation) !!}</span>
      <span class="text-xs pr-1 col-span-2 flex justify-end">
        @php
          $message = \Xenonwellz\Messenger\Models\Message::where('sender_id', $conversation->id)
              ->where('receiver_id', auth()->user()->id)
              ->where('read_at', null)
              ->get('message');
          if ($message->count() >= 100) {
              echo '99+';
          } elseif ($message->count() > 0) {
              echo '<small class="h-4 px-1 bg-red-700 text-white rounded-full font-bold transform">' . $message->count() . '</small>';
          }
        @endphp
      </span>
      </p>
    </div>
  </div>
@endforeach
