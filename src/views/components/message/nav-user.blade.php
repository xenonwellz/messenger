<i class="bi bi-caret-left-fill text-2xl text-gray-700 sm:hidden cursor-pointer" onclick="hideMessageContainer()"></i>
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
class="rounded-full p-1 pl-0 h-12">

<div class="pb-1 pl-2">
  <b class="text-sm text-gray-800">{{ $user->name }}</b>
  <p class="text-xs">Online</p>
</div>
