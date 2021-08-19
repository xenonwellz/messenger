<span data-src="{{ $message->attachment_path }}">
  @if (isWebImage('/storage' . $message->attachment_path))
    <div class="">
      <img class="rounded-lg py-1" data-zoom="" style="width: 250px; height: 250px; object-fit: cover;"
        src="/storage{{ $message->attachment_path }}">
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
</span>
