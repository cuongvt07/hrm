<?php
namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadHelper
{
    /**
     * Upload one or many files to storage and return their paths and metadata
     * @param array $files Array of UploadedFile objects
     * @param string $folder Storage folder (default: 'documents')
     * @param string $disk Storage disk (default: 'public')
     * @param int|null $nhanVienId Employee ID
     * @param int|null $userId Uploader ID
     * @return array Array of uploaded file info: [ 'id', 'path', 'name', 'mime', 'size' ]
     */
    public static function uploadFiles(array $files, $folder = 'documents', $disk = 'public', $nhanVienId = null, $userId = null)
    {
        $result = [];
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $fileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $filePath = $file->storeAs($folder, $fileName, $disk);

                // Save to database
                $tepTin = \App\Models\TepTin::create([
                    'nhan_vien_id' => $nhanVienId,
                    'loai_tep'     => $folder,
                    'ten_tep'      => $file->getClientOriginalName(),
                    'duong_dan_tep' => $filePath,
                    'kieu_mime'    => $file->getMimeType(),
                    'nguoi_tai_len' => $userId,
                ]);


                $result[] = [
                    'id'   => $tepTin->id,
                    'path' => $filePath,
                    'name' => $file->getClientOriginalName(),
                    'mime' => $file->getMimeType(),
                    'size' => $file->getSize(),
                ];
            }
        }
        return $result;
    }
}