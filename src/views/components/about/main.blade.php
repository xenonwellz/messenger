<div class="flex items-center md:hidden py-2">
  <span class="cursor-pointer" onclick="hideAboutContainer()">
    <i class="bi bi-caret-left-fill text-xl text-gray-700"></i> <span class="text-gray-700">Back</span>
  </span>
</div>
<div class="text-center">
  <div class="flex items-center justify-center px-5 pt-8">
    <img src="
    @if (config('messenger.use_avatar_field') && $user[config('messenger.avatar_field_name')]) {{ $user[config('messenger.avatar_field_name')] }}
  @elseif (config('messenger.default_avatar'))
                        @if (asset(config('messenger.default_avatar')))
    {{ asset(config('messenger.default_avatar')) }}
  @else {{ config('messenger.default_avatar') }} @endif
  @else
    https://ui-avatars.com/api/?background=random&name={{ str_replace(' ', '+', trim($user->name)) }}
    @endif " alt="
    {{ $user->name }}"
    class="rounded-full w-full"
    style="min-width:140px; max-width: 200px;">
  </div>
  <div class="py-6">
    <b class="text-xl text-gray-700">{{ $user->name }}</b>
    <p class="text-red-500" id="delete-conversation-button">
      <span class="cursor-pointer" onclick="deleteConversation()">
        <i class="bi bi-trash"></i>&nbsp;Delete&nbsp;Conversation
      </span>
    </p>
  </div>
</div>
