<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\File;

trait ImageUploader
{
    public function uploadImage(UploadedFile $file, int $width, int $height, string $folder, int $compressionQuality = 75)
    {
        $manager = new ImageManager(new Driver());

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $path = public_path($folder);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $img = $manager->read($file);
        $img->resize($width, $height);

        $img->save($path . '/' . $filename, $compressionQuality);

        $filePath = $path . '/' . $filename;
        if (filesize($filePath) > 102400) {
            $compressionQuality = 50;
            $img->save($filePath, $compressionQuality);
        }

        return $filename;
    }


    public function deleteOne($directory, $filename)
    {
        $filePath = public_path($directory . DIRECTORY_SEPARATOR . $filename);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
