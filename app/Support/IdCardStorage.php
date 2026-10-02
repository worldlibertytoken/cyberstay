<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class IdCardStorage
{
    public function store(?UploadedFile $file, string $folder, ?string $current = null): ?string
    {
        if (! $file) {
            return $current;
        }

        if ($current) {
            Storage::disk('public')->delete($current);
        }

        return $file->store($folder, 'public');
    }
}
