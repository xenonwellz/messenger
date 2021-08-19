<div
  class="fixed overflow-hidden top-0 left-0 w-full h-full z-100 flex bg-black-400 bg-opacity-60 hidden justify-center items-center"
  id="message-menu">
  <div class="w-64 rounded-xl bg-gray-300 border-gray-400 border" style="list-style-type: none;">
    <li class="appearance-none w-full border-b border-gray-400 py-3 flex justify-center items-center cursor-pointer"
      id="menu-copy-btn" onclick="copyMessage()">Copy</li>
    <li class="appearance-none w-full border-b border-gray-400 py-3 flex justify-center items-center cursor-pointer"
      id="menu-download-btn" onclick="downloadAttachment()">Download <a href="http://" class="hidden" id="#download-btn"
        target="_blank" rel="noopener noreferrer" download></a></li>
    <li class="appearance-none w-full border-b border-gray-400 py-3 flex justify-center items-center cursor-pointer"
      id="menu-unsend-btn" onclick="unsendMessage()">Unsend</li>
    <li class="appearance-none w-full border-b border-gray-400 py-3 flex justify-center items-center cursor-pointer"
      onclick="deleteMessage()">Delete</li>
    <li class="appearance-none w-full border-gray-400 py-3 flex justify-center items-center cursor-pointer"
      onclick="hideMenu()">Cancel</li>
  </div>
</div>
