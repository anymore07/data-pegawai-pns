<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;

class Miscellaneous {
    public function uploadImageToStorage(?UploadedFile $file, string $folder = 'folder_image'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $path = $file->store($folder, 'public');
        return asset('storage/' . $path);
    }
}
