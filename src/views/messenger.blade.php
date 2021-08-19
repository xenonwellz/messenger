@include('messenger::components.header')

<div class="row mx-auto grid grid-cols-12 h-screen max-h-screen">

  <div class="col-span-12 sm:col-span-4 md:col-span-3 px-0 pt-2">
    <div class="flex justify-content-center col-12 p-2">
      <input type="text" name="search" id="search-input" class="rounded-full shadow appearance-none border leading-tight w-full py-2 px-3 text-gray-700 focus:outline-none 
         focus:ring focus:ring-blue-500 focus:border-blue-500 focus:ring-opacity-50 focus:border-none"
        placeholder="Search Conversation">
    </div>
    <div class="relative">
      <div class="absolute w-full h-full bg-white top-0 left-0 z-10 hidden px-2 overflow-auto"
        style="height: calc(100vh - 4rem - 10px)" id="search-conversations">
      </div>
      <div class="my-3 p-1 border-t border-b flex flex-row overflow-auto" id="online-users-container">
        <div class="h-14 w-full text-gray-400 flex" id="online-users-cont">
          <span class="flex items-center justify-center w-full">No online users.</span>
        </div>
      </div>

      <div id="recent-users-container" class="p-2 overflow-y-auto" style="max-height: calc(100vh - 150px)"></div>
    </div>

  </div>

  <div class="col-span-12 sm:col-span-8 md:col-span-6 border-l-2 border-r-2 fixed sm:relative top-0 left-0
     w-full sm:w-auto h-full bg-white hidden sm:block" id="message_container">

    @include('messenger::components.message.message-box-navigation')
    @include('messenger::components.message.message-box')
    @include('messenger::components.message.message-footer')


  </div>

  <div class="hidden md:block w-full h-full fixed md:relative md:col-span-3 bg-white" id="about-container">
    <div class="h-full w-full flex items-center justify-center text-gray-400">No user Selected</div>
  </div>
</div>
@include('messenger::components.message.message-menu')
@include('messenger::components.footer')
