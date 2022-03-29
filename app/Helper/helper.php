<?php
/**
 * created by: tushar Khan
 * email : tushar.khan0122@gmail.com
 * date : 3/29/2022
 */

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
