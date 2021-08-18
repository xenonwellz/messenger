@foreach ($messages as $message)
  @if ($message->sender_id == auth()->user()->id)
    @include('messenger::components.message.sent')
  @else
    @include('messenger::components.message.received')
  @endif
@endforeach
