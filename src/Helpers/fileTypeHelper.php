<?php

use Illuminate\Support\Facades\Storage;


if (!function_exists('isWebImage')) {
    function isWebImage($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return in_array($info, ['jpg', 'png', 'gif', 'webp']);
    }

    function isAudio($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return in_array($info, ['wav', 'mp3']);
    }

    function isVideo($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return in_array($info, ['mp4', 'mov', 'webm', 'mkv']);
    }

    function isPDF($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return $info == 'pdf';
    }

    function isWordDocument($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return in_array($info, ['doc', 'docx', 'odt']);
    }

    function isArchieve($path)
    {
        $info = pathinfo(storage_path() . $path)['extension'];
        return in_array($info, ['tar.gz', 'tar.bz', 'zip', '7z', 'rar']);
    }

    function getFullPath($path)
    {
        return Storage::get($path);
    }
}
