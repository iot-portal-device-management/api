<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponses
{
    /*
    |--------------------------------------------------------------------------
    | API responses
    |--------------------------------------------------------------------------
    |
    | This section contains the API response functions for returning a API response
    | to the client for most response codes.
    |
    */

    public function apiOk(
        array|string $result = [],
        bool $success = true,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_OK
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    public function apiBadRequest(
        array|string $result = [],
        bool $success = false,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_BAD_REQUEST
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    public function apiUnauthorized(
        array|string $result = [],
        bool $success = false,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_UNAUTHORIZED
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    public function apiNotFound(
        array|string $result = [],
        bool $success = false,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_NOT_FOUND
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    public function apiUnprocessableEntity(
        array|string $result = [],
        bool $success = false,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_UNPROCESSABLE_ENTITY
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    public function apiInternalServerError(
        array|string $result = [],
        bool $success = false,
        array $errors = [],
        array $message = [],
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        return response()->json([
            'result' => $result,
            'success' => $success,
            'errors' => $errors,
            'messages' => $message,
        ], $status);
    }

    /*
    |--------------------------------------------------------------------------
    | API MQTT responses
    |--------------------------------------------------------------------------
    |
    | This section contains the API response functions for returning a API response
    | to MQTT server for most response codes.
    |
    */

    public function apiMqttOk(array|string $result = [], int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json(['result' => $result], $status);
    }

    public function apiMqttOkWithDisallowedTopics(
        array|string $result = [],
        array $topics = [],
        int $status = Response::HTTP_OK
    ): JsonResponse
    {
        return response()->json(['result' => $result, 'topics' => $topics], $status);
    }

    public function apiMqttBadRequest(array|string $result = [], int $status = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json(['result' => $result], $status);
    }

    public function apiMqttUnauthorized(
        array|string $result = [],
        int $status = Response::HTTP_UNAUTHORIZED
    ): JsonResponse
    {
        return response()->json(['result' => $result], $status);
    }

    public function apiMqttNotFound(array|string $result = [], int $status = Response::HTTP_NOT_FOUND): JsonResponse
    {
        return response()->json(['result' => $result], $status);
    }

    public function apiMqttInternalServerError(
        array|string $result = [],
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse
    {
        return response()->json(['result' => $result], $status);
    }
}
