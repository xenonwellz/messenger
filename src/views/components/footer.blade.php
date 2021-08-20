<div class="fixed h-full w-full  bg-black-400 bg-opacity-90  top-0 left-0 hidden" id="full-overlay"
  style="z-index: 50;"></div>
<script src="/cdn/jquery/jquery-3.6.0.min.js"></script>
<script src="/cdn/popper/popper.min.js"></script>
<script src="/cdn/echo/echo.js"></script>
<script src="/cdn/pusher/pusher.min.js"></script>
<script src="/cdn/sweetalert/sweetalert.min.js"></script>
@include('messenger::components.js.js')
<script>
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
</script>
</body>

</html>
