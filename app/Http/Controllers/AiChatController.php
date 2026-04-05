<?php

namespace App\Http\Controllers;

use App\Services\GeminiVehicleSupportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AiChatController extends Controller
{
    public function __construct(private readonly GeminiVehicleSupportService $geminiVehicleSupportService)
    {
    }

    public function ask(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $result = $this->geminiVehicleSupportService->ask($request->user(), $validated['message']);

        return response()->json([
            'ok' => $result['ok'],
            'reply' => $result['reply'],
        ], $result['ok'] ? 200 : 422);
    }
}
