@foreach ($messages as $message)
  @include('messenger::components.message.for-received')
@endforeach
