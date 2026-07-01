<?php

namespace App\Services\Uploadfile;

use App\Http\Requests\ImageUploadRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FotoUserService
{
    public function uploadTempImage(ImageUploadRequest $request)
    {
        $path = $request->file('image')->store('', 'temp');
        return session()->flash('pathTemp', 'temp/' . $path);
    }

    public function moveImageFromTemp(?string $imageUrl, ?string $role): ?string
    {
        if (empty($imageUrl)) {
            return null;
        }

        // extract just the filename to avoid empty or directory paths
        $imageUrlNew = basename($imageUrl);

        $temp = Storage::disk('temp');

        // ensure the file exists on the temp disk
        if (! $temp->exists($imageUrlNew)) {
            return null;
        }

        $sourcePath = $temp->path($imageUrlNew);

        // tentukan folder tujuan di public/image/user
        $destinationDir = storage_path('app/public/images/' . $role);

        // pastikan folder tujuan ada
        if (! File::exists($destinationDir)) {
            File::makeDirectory($destinationDir, 0755, true);
        }

        $moved = false;
        // pindahkan file dari temp ke tujuan only if it's a real file
        if (File::exists($sourcePath) && File::isFile($sourcePath)) {
            $destinationPath = $destinationDir . DIRECTORY_SEPARATOR . $imageUrlNew;
            File::move($sourcePath, $destinationPath);
            $moved = true;
        }

        // hapus semua isi folder temp
        $allTempFiles = $temp->allFiles();
        if (! empty($allTempFiles)) {
            $temp->delete($allTempFiles);
        }

        return 'images/' . $role . '/' . $imageUrlNew;
    }
}
