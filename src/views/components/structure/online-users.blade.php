@foreach ($users as $user)
  <span class="mr-1 active_user relative flex-shrink-0 cursor-pointer" onclick="showMessageContainer('1')">
    <img src="
    @if (config('messenger.use_avatar_field') && $user[config('messenger.avatar_field_name')]) {{ $user[config('messenger.avatar_field_name')] }}
@elseif (config('messenger.default_avatar'))
               @if (asset(config('messenger.default_avatar')))
    {{ asset(config('messenger.default_avatar')) }}
  @else {{ config('messenger.default_avatar') }} @endif
  @else
    https://ui-avatars.com/api/?background=random&name={{ str_replace(' ', '+', trim($user->name)) }}
    @endif " alt="
    {{ $user->name }}" class="rounded-full h-14 inline-flex">
    <span class="rounded-full absolute bg-green-500 h-auto border-white border-4 box-content bottom-0 right-0"
      style="padding: 6px"></span>
  </span>
@endforeach
