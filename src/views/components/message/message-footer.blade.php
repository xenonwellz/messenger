<div class="flex h-12 px-2 pt-2 hidden relative" id="message-footer">
  <div
    class="absolute bg-white -top-12 h-full w-full flex items-center flex-row overflow-y-auto h-12 left-0 px-2 hidden"
    id="files-viewer">
  </div>
  <label for="message-file"
    class="h-9 w-9 flex items-center rounded-full bg-blue-500 border-blue-500 border justify-center flex-shrink-0 cursor-pointer">
    <i class="bi bi-file-earmark-plus text-xl text-white"></i>
    <input type="file" name="message-file" id="message-file" class="hidden" multiple>
  </label>
  <textarea type="text" id="message-input" placeholder="Enter Message..."
    class="w-5 flex-grow mx-2 p-1 px-2 h-9 focus:outline-none rounded-full border resize-none shadow focus:ring focus:ring-blue-500 focus:border-blue-500 focus:ring-opacity-50 focus:border-none"></textarea>
  <span onclick="sendMessage()"
    class="h-9 w-9 flex items-center rounded-full justify-center bg-blue-500 border-blue-500 border flex-shrink-0 cursor-pointer">
    <i class="text-white text-xl pl-1">
      <svg width="1em" height="1em" version="1.1" viewBox="0 0 16.006 15.995" xmlns="http://www.w3.org/2000/svg"
        xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/"
        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
        <g transform="translate(-13.305 -178.36)" color="inherit">
          <path stroke="currentcolor" d="m28.371 186.36h-12.494m-1.9361-7.21 14.43 7.21-14.43 7.21 1.8534-7.21z"
            fill="none" stroke="#000" stroke-width=".84038" />
        </g>
      </svg>
    </i>
  </span>
</div>
