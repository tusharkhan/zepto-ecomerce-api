<?php
/**
 * created by: tushar Khan
 * email : tushar.khan0122@gmail.com
 * date : 3/29/2022
 */

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if ( ! function_exists('sendResponse') ){
    /**
     * @param $message
     * @param $result
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function sendResponse($message, $result, int $code = 200)
    {
        $response = [
            'code' => $code,
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }
}


if ( ! function_exists('sendError') ){
    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    function sendError($error, array $errorMessages = [], int $code = 404)
    {
        $response = [
            'code' => $code,
            'success' => false,
            'message' => $error,
            'data' => [],
            'errors' => $errorMessages,
        ];

        return response()->json($response, $code);
    }
}


if ( ! function_exists('uploadImage') ){
    function uploadImage(UploadedFile $uploadedFile, $folder = null, $disk = 'public', $filename = null) {

        $name = !is_null($filename) ? $filename : Str::random(25) . '_' . time();

        searchFolderOrCreate($folder, $disk);

        $uploadedFile = $uploadedFile->storeAs($folder, $name . '.' . $uploadedFile->getClientOriginalExtension(), $disk);
        return explode('/', $uploadedFile)[1];
    }
}


if ( ! function_exists('searchFolderOrCreate') ){
    function searchFolderOrCreate($path, $disk) {
        if (!Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->makeDirectory($path);
            artisanCall('storage:link');
        }
    }
}



if ( ! function_exists('artisanCall') ){
    function artisanCall($command) {
        Artisan::call($command);
    }
}


if ( ! function_exists('createSlug') ){
    function createSlug($string, $separator = '-') {
        return Str::random(4) . '-'.Str::slug($string, $separator);
    }
}
