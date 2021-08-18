@if (isWebImage('/storage' . $message->attachment_path))
  <div class="relative flex justify-center items-center overflow-hidden mb-1 rounded-lg mt-1"
    style="width: 250px; max-height: 250px;">
    <img class="" style="min-width: 100%; min-height: 100%;" src="/storage{{ $message->attachment_path }}">
  </div>
@elseif (isAudio($message->attachment_path))
  <span class="font-bold pl-1"><i class="bi bi-file-earmark-music"></i> Audio Attachment</span>
@elseif (isVideo($message->attachment_path))
  <span class="font-bold pl-1"><i class="bi bi-file-earmark-play"></i> Video Attachment</span>
@elseif (isPDF($message->attachment_path))
  <span class="font-bold pl-1"><i class="bi bi-file-earmark-pdf"></i> PDF Attachment</span>
@elseif (isWordDocument($message->attachment_path))
  <span class="font-bold pl-1"><i class="bi bi-file-earmark-word"></i> Word Attachment</span>
@elseif (isArchieve($message->attachment_path))
  <span class="font-bold pl-1"><i class="bi bi-file-earmark-zip"></i> Archieve Attachment</span>
@else
  <span class="font-bold pl-1"><i class="bi bi-file-earmark"></i> Unknown Attachment</span>

@endif
