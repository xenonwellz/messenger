<div class="fixed h-full w-full  bg-black-400 bg-opacity-90  top-0 left-0 hidden" id="full-overlay"
  style="z-index: 50;"></div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"
integrity="sha512-XVnzJolpkbYuMeISFQk6sQIkn3iYUbMX3f0STFUvT6f4+MZR6RJvlM5JFA2ritAN3hn+C0Bkckx2/+lCoJl3yg=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.1.4/sweetalert2.min.js"
integrity="sha512-Lbwer45RtGISU+efaUoil1EFYFliqkKOaZhUMXG8RoZZ5fdjpK4S/2khwZynw8vyItDeaRZ+IE6XdKA6XCsyxQ=="
crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('xmassets/js/echo.js') }}"></script>
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
  let urlInitial = "/" + "{{ config('messenger.route_prefix') }}" + "/"
  let maxFileAtOnce = {{ config('messenger.max_file_at_once') }};
  const userId = {{ auth()->user()->id }};
  let typingTimer;
  let tz = new Date().getTimezoneOffset();

  @if (config('messenger.websocket_provider') == 'pusher')
    window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ env('PUSHER_APP_KEY') }}",
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    encrypted: true,
    });
  
  @else
    window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ env('PUSHER_APP_KEY') }}",
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS:@if (config('messenger.force_websocket_ssl/tls')) true @else false @endif,
    wsHost: window.location.hostname,
    wsPort: 6001,
    });
  @endif
</script>
<script src="{{ asset('xmassets/js/messenger.js') }}"></script>
</body>

</html>
