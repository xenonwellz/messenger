<div class="inline-flex mb-2 justify-end" id="sent-{{ $message->id }}"
  oncontextmenu="showSentMenu({{ $message->id }})">
  <div class="sent bg-blue-500 rounded-lg text-gray-100 px-2 text-sm py-1 max-w-65/100 cursor-pointer select-none"">
          @if ($message->message)
    <span class=" whitespace-pre-wrap">{{ trim($message->message) }}</span>
  @else
    @include('messenger::components.message.attachment')
    @endif
    <div class="justify-end text-white text-dark flex items-center" style="font-size: 0.55rem; line-height: 0.55rem;">
      {{ $message->created_at->addMinutes($tz)->format('H:i') }}
      <span class="text-sm">
        @if ($message->read_at)
          <i class="bi bi-check-all pl-1 text-green-900"></i>
        @elseif($message->delivered_at)
          <i class="bi bi-check-all pl-1"></i>
        @else
          <i class="bi bi-check pl-1"></i>
        @endif
      </span>
    </div>
  </div>
</div>
