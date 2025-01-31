<?php

namespace App\Api\V1\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseApiController extends Controller
{
    /**
     * @param array $result
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendResponse(mixed $result, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'data' => $result,
        ], $code);
    }

    /**
     * @param string $message
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendResponseMessage(string $message, int $code = Response::HTTP_OK): JsonResponse
    {
        return response()->json(compact('message'), $code);
    }

    /**
     * @param string $errorMessage
     * @param int $code
     * @param array $data
     *
     * @return JsonResponse
     */
    public function sendError(string $errorMessage = 'Not found!', int $code = Response::HTTP_NOT_FOUND, array $data = []): JsonResponse
    {
        $errors = ['message' => $errorMessage];

        if ($data) {
            $errors['errors'] = $data;
        }

        return response()->json($errors, $code);
    }

    /**
     * @return JsonResponse
     */
    public function sendInternalError(): JsonResponse
    {
        return $this->sendError(__('auth.sever_error'), Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
