<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait CompressesImages
{
    protected function compressAndStore(UploadedFile $file, string $path, int $quality = 80, int $maxWidth = 800): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = time() . '_' . uniqid() . '.jpg';
        $fullPath = storage_path('app/public/' . $path . '/' . $filename);
        
        // Ensure directory exists
        if (!file_exists(dirname($fullPath))) {
            mkdir(dirname($fullPath), 0755, true);
        }
        
        // Create image resource based on file type
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getPathname());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getPathname());
                break;
            case 'gif':
                $image = imagecreatefromgif($file->getPathname());
                break;
            default:
                $image = imagecreatefromjpeg($file->getPathname());
        }
        
        if (!$image) {
            return $file->store($path, 'public');
        }
        
        $originalWidth = imagesx($image);
        $originalHeight = imagesy($image);
        
        // Calculate new dimensions
        if ($originalWidth > $maxWidth) {
            $newWidth = $maxWidth;
            $newHeight = ($originalHeight * $maxWidth) / $originalWidth;
        } else {
            $newWidth = $originalWidth;
            $newHeight = $originalHeight;
        }
        
        // Create new image
        $newImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
        
        // Save compressed image
        imagejpeg($newImage, $fullPath, $quality);
        
        // Clean up memory
        imagedestroy($image);
        imagedestroy($newImage);
        
        return $path . '/' . $filename;
    }
}