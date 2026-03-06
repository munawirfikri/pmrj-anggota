<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Http\UploadedFile;

trait CompressesImages
{
    protected function compressAndStore(UploadedFile $file, string $path, int $quality = 80, int $maxWidth = 800): string
    {
        $manager = new ImageManager(new Driver());
        
        $image = $manager->read($file->getPathname());
        
        // Resize if width is larger than maxWidth
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }
        
        // Generate filename
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = storage_path('app/public/' . $path . '/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        // Save compressed image
        $image->toJpeg($quality)->save($fullPath);
        
        return $path . '/' . $filename;
    }
}