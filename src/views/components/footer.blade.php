<div class="fixed h-full w-full  bg-black-400 bg-opacity-90  top-0 left-0 hidden" id="full-overlay"
  style="z-index: 50;"></div>
<script src="/cdn/jquery/jquery-3.6.0.min.js"></script>
<script src="/cdn/popper/popper.min.js"></script>
<script src="/cdn/echo/echo.js"></script>
<script src="/cdn/pusher/pusher.min.js"></script>
<script src="/cdn/sweetalert/sweetalert.min.js"></script>
@include('messenger::components.js.js')
<script>
  const userId = {{ auth()->user()->id }};
  window.Echo = new Echo({
    broadcaster: 'pusher',
    key: "{{ env('PUSHER_APP_KEY') }}",
    cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
    forceTLS: false,
    wsHost: window.location.hostname,
    wsPort: 6001,
  });

  window.Echo.private('message')
    .listen('.messenger', (e) => {
      console.log(e);
    });

  window.Echo.private('message')
    .listenForWhisper('.typing', (e) => {
      console.log(e);
    });

  function isTyping() {
    setTimeout(function() {
      window.Echo.private('message').whisper('typing', {
        user: userId,
        typing: true
      });
    }, 300);
  }

  let typingTimer;
  Echo.private('message')
    .listenForWhisper('typing', (e) => {
      if (e.typing) {
        $('#conversation-text-' + e.user).addClass('hidden')
        $('#conversation-typing-' + e.user).removeClass('hidden')
        if (e.user == $('#message-box').attr('data-id')) {
          $("#message-box-online").addClass('hidden')
          $("#message-box-typing").removeClass('hidden')
        }
      } else {
        $('#conversation-text-' + e.user).removeClass('hidden')
        $('#conversation-typing-' + e.user).addClass('hidden')
        if (e.user == $('#message-box').attr('data-id')) {
          // $("#message-box-online").addClass('hidden')
          $("#message-box-typing").addClass('hidden');
        }
      }
      clearTimeout(typingTimer);
      typingTimer = setTimeout(function() {
        window.Echo.private('message').whisper('typing', {
          user: userId,
          typing: false
        });
      }, 900);
    });

  $('#message-input').on('keyup', function() {
    isTyping();
  })
</script>
</body>

</html>
