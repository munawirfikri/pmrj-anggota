<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class KtpIndonesiaRule implements Rule
{
    public function passes($attribute, $value)
    {
        if (!$value instanceof UploadedFile) {
            return false;
        }

        // Check if it's an image
        if (!in_array($value->getMimeType(), ['image/jpeg', 'image/png', 'image/jpg'])) {
            return false;
        }

        // Get image content for text detection
        $imagePath = $value->getRealPath();
        
        try {
            // Simple text detection using basic image analysis
            $imageContent = $this->extractTextFromImage($imagePath);
            
            // Check for KTP indicators
            $ktpIndicators = [
                'REPUBLIK INDONESIA',
                'KARTU TANDA PENDUDUK',
                'NIK',
                'Nama',
                'Tempat/Tgl Lahir',
                'Jenis Kelamin',
                'Alamat',
                'RT/RW',
                'Kel/Desa',
                'Kecamatan',
                'Agama',
                'Status Perkawinan',
                'Pekerjaan',
                'Kewarganegaraan',
                'Berlaku Hingga',
                'SEUMUR HIDUP'
            ];
            
            $foundIndicators = 0;
            foreach ($ktpIndicators as $indicator) {
                if (stripos($imageContent, $indicator) !== false) {
                    $foundIndicators++;
                }
            }
            
            // Must have at least 3 KTP indicators
            return $foundIndicators >= 3;
            
        } catch (\Exception $e) {
            // If OCR fails, do basic validation
            return $this->basicKtpValidation($imagePath);
        }
    }

    private function extractTextFromImage($imagePath)
    {
        // Simple OCR using Tesseract if available
        if (function_exists('exec')) {
            $output = [];
            exec("tesseract {$imagePath} stdout 2>/dev/null", $output);
            return implode(' ', $output);
        }
        
        // Fallback: basic image analysis
        return $this->basicImageAnalysis($imagePath);
    }
    
    private function basicImageAnalysis($imagePath)
    {
        // Basic validation: check image dimensions (KTP usually landscape)
        $imageInfo = getimagesize($imagePath);
        if ($imageInfo) {
            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $ratio = $width / $height;
            
            // KTP ratio is approximately 1.6:1 (landscape)
            return $ratio > 1.4 && $ratio < 2.0;
        }
        
        return false;
    }
    
    private function basicKtpValidation($imagePath)
    {
        // Check image dimensions and aspect ratio
        $imageInfo = getimagesize($imagePath);
        if (!$imageInfo) {
            return false;
        }
        
        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $ratio = $width / $height;
        
        // KTP should be landscape with specific ratio
        if ($ratio < 1.4 || $ratio > 2.0) {
            return false;
        }
        
        // Check minimum resolution
        if ($width < 400 || $height < 250) {
            return false;
        }
        
        return true;
    }

    public function message()
    {
        return 'File yang diupload harus berupa foto KTP Indonesia yang valid';
    }
}
