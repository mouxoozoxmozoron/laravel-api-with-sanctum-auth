<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;

trait FileTrait
{
    function storeBase64File($base64String, $storagePath)
    {
        try {
            // Decode the base64 string to binary data
            $binaryData = base64_decode($base64String);

            // Generate a unique file name
            $fileName = uniqid('img_') . '.' . 'webp';

            // Create the full path within the storage directory
            $filePath = $storagePath . '/' . $fileName;

            // Store the file in Laravel's storage
            Storage::disk('public')->put($filePath, $binaryData);

            // Return the path with the generated image name
            return $filePath;

        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'message' => "something went wrong in FileTrait.storeBase64Image"
            ], 500);
        }
    }


    function getFileExtensionFromBase64($base64String)
    {
        // Extract the mime type from the base64 string
        preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches);

        // If a mime type is found, return the corresponding file extension
        return isset($matches[1]) ? $matches[1] : null;
    }

    public function checkIfFileExist($storagePath)
    {
        return Storage::disk('public')->exists($storagePath);
    }

    public function deleteFileFromStorage($storagePath)
    {
        if ($this->checkIfFileExist($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }
}
