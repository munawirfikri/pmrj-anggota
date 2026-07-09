<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

trait CompressesImages
{
    protected function compressAndStore(UploadedFile $file, string $path, ?string $identifier = null, int $quality = 80, int $maxWidth = 800): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();

        if ($extension === 'pdf' || $mimeType === 'application/pdf') {
            // Validate PDF Magic Bytes
            $handle = fopen($file->getPathname(), 'rb');
            if ($handle) {
                $header = fread($handle, 4);
                fclose($handle);
                if ($header !== '%PDF') {
                    throw new \InvalidArgumentException('Berkas PDF tidak valid.');
                }
            } else {
                throw new \InvalidArgumentException('Tidak dapat membaca berkas.');
            }

            // Scan for active content / scripts in PDF
            $content = file_get_contents($file->getPathname());
            if (preg_match('/\/(JS|JavaScript|Launch|XFA|RichMedia)(?=[\s()<>\[\]{}%\/]|$)/i', $content)) {
                throw new \InvalidArgumentException('Berkas PDF mengandung konten aktif atau skrip yang tidak didukung demi keamanan.');
            }

            // Secure filename generation
            $cleanIdentifier = $identifier ? \Illuminate\Support\Str::slug($identifier, '_') : uniqid();
            $randomSuffix = strtolower(\Illuminate\Support\Str::random(5));
            $prefix = $path === 'photos' ? 'foto' : $path;
            $filename = $prefix . '_' . $cleanIdentifier . '_' . $randomSuffix . '_' . time() . '.pdf';
            $fullPath = storage_path('app/public/' . $path . '/' . $filename);

            // Ensure directory exists
            if (!file_exists(dirname($fullPath))) {
                mkdir(dirname($fullPath), 0755, true);
            }

            // Move the file securely
            if (!copy($file->getPathname(), $fullPath)) {
                throw new \RuntimeException('Gagal menyimpan berkas PDF.');
            }

            return $path . '/' . $filename;
        }

        $cleanIdentifier = $identifier ? \Illuminate\Support\Str::slug($identifier, '_') : uniqid();
        $randomSuffix = strtolower(\Illuminate\Support\Str::random(5));
        $prefix = $path === 'photos' ? 'foto' : $path;
        $filename = $prefix . '_' . $cleanIdentifier . '_' . $randomSuffix . '_' . time() . '.jpg';
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