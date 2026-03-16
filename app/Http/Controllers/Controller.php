<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    protected function okResponse(mixed $data = null, string $message = 'Opération réussie', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $status);
    }

    protected function failResponse(mixed $errors = [], string $message = 'Erreur de validation', int $status = 422): JsonResponse
    {
        return response()->json([
            'success' => false,
            'errors' => $errors,
            'message' => $message,
        ], $status);
    }
}
