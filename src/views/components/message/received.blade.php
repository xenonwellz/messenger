<div class="inline-flex mb-2" id="received-{{ $message->id }}" oncontextmenu="showReceivedMenu({{ $message->id }})">
  <div class="received bg-blue-300 rounded-lg text-gray-700 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none">
    @if ($message->message)
      <span class=" whitespace-pre-wrap">{{ trim($message->message) }}</span>
    @else
      @include('messenger::components.message.attachment')
    @endif
    <div class="justify-end text-gray-900 text-dark flex" style="font-size: 0.55rem; line-height: 0.55rem;">
      {{ $message->created_at->addMinutes($tz)->format('H:i') }}
    </div>
  </div>
</div>
