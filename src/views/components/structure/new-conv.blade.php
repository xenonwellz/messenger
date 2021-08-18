<div class="h-full w-full flex justify-center items-center text-gray-400 flex-col">
  <div class="w-2/4 relative flex flex-shrink">
    @include('messenger::components.svg.svg')
  </div>
  {{ $message }}{{ $user->name }}
</div>
