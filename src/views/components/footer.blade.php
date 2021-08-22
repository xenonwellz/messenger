<div class="fixed h-full w-full  bg-black-400 bg-opacity-90  top-0 left-0 hidden" id="full-overlay"
  style="z-index: 50;"></div>
<script src="/cdn/jquery/jquery-3.6.0.min.js"></script>
<script src="/cdn/popper/popper.min.js"></script>
<script src="/cdn/echo/echo.js"></script>
<script src="/cdn/pusher/pusher.min.js"></script>
<script src="/cdn/sweetalert/sweetalert.min.js"></script>
<script>
  let csrf = "{{ csrf_token() }}";
  let increaseSearchCount = true;
  let increaseGetConversatinCount = true;
  let getConversationsCount = 10;
  let messageOffset = 0;
  let searchCount = 10;
  let message_scroll_wait = false;
  let noMoreMessage = false;
  let newMessageCount = 1;
  let maxFileSize = {{ config('messenger.max_file_size') }} * 1024;
  let urlInitial = "/messenger/"
  let maxFileAtOnce = {{ config('messenger.max_file_at_once') }};
  const userId = {{ auth()->user()->id }};
  let typingTimer;
  let tz = new Date().getTimezoneOffset();

  @if (config('messenger.websocket_provider') == 'pusher')
  
  @else
    window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ env('PUSHER_APP_KEY') }}",
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
    });
  @endif
</script>
@include('messenger::components.js.js')
</body>

</html>
