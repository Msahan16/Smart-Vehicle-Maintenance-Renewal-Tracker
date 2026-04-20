<?php

namespace App\Services;

use App\Models\MaintenanceRecord;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiVehicleSupportService
{
    public function ask(User $user, string $question): array
    {
        $apiKey = (string) config('services.gemini.api_key');
        $model = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $fallbackModel = (string) config('services.gemini.fallback_model', 'gemini-2.5-flash-lite');
        $baseUrl = rtrim((string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $verifySsl = (bool) config('services.gemini.verify_ssl', true);

        if ($apiKey === '') {
            return [
                'ok' => false,
                'reply' => 'AI chat is not configured yet. Please add GEMINI_API_KEY to your environment settings.',
            ];
        }

        $context = $this->buildUserContext($user);

        $systemInstruction = <<<TXT
You are Vehicle Support AI for a vehicle maintenance and renewal platform.

Response policy:
- Be concise, practical, and friendly.
- Use the USER DATA section as the highest-priority source for date-specific answers.
- If user data is missing, clearly say what is missing and offer a general recommendation.
- Never invent exact dates, records, fines, or legal facts.
- For legal/safety compliance topics, include a brief disclaimer that rules vary by region.
- Format with short paragraphs and bullet points when useful.
TXT;

        $userPrompt = "USER DATA:\n{$context}\n\nQUESTION:\n{$question}";

        $client = Http::timeout(25)->acceptJson();

        if (! $verifySsl) {
            $client = $client->withoutVerifying();
        }

        $modelsToTry = array_values(array_unique(array_filter([$model, $fallbackModel])));
        $lastStatus = null;
        $lastErrorMessage = null;

        foreach ($modelsToTry as $modelToTry) {
            try {
                $response = $client
                    ->post("{$baseUrl}/models/{$modelToTry}:generateContent?key={$apiKey}", [
                        'systemInstruction' => [
                            'parts' => [
                                ['text' => $systemInstruction],
                            ],
                        ],
                        'contents' => [
                            [
                                'role' => 'user',
                                'parts' => [
                                    ['text' => $userPrompt],
                                ],
                            ],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.4,
                            'maxOutputTokens' => 500,
                        ],
                    ]);
            } catch (\Throwable $exception) {
                Log::warning('Gemini vehicle support request exception', [
                    'user_id' => $user->id,
                    'model' => $modelToTry,
                    'message' => $exception->getMessage(),
                ]);

                $lastStatus = 0;
                $lastErrorMessage = $exception->getMessage();
                continue;
            }

            if (! $response->ok()) {
                $errorMessage = data_get($response->json(), 'error.message');

                Log::warning('Gemini vehicle support request failed', [
                    'user_id' => $user->id,
                    'model' => $modelToTry,
                    'status' => $response->status(),
                    'error' => is_string($errorMessage) ? $errorMessage : null,
                    'body' => mb_substr((string) $response->body(), 0, 500),
                ]);

                $lastStatus = $response->status();
                $lastErrorMessage = is_string($errorMessage) ? trim($errorMessage) : null;
                continue;
            }

            $reply = data_get($response->json(), 'candidates.0.content.parts.0.text');

            if (! is_string($reply) || trim($reply) === '') {
                $lastStatus = $response->status();
                $lastErrorMessage = 'Empty response from AI provider.';
                continue;
            }

            return [
                'ok' => true,
                'reply' => trim($reply),
            ];
        }

        if (app()->environment('local') && is_string($lastErrorMessage) && $lastErrorMessage !== '') {
            return [
                'ok' => false,
                'reply' => 'Gemini API error ('.($lastStatus ?? 0).'): '.$lastErrorMessage,
            ];
        }

        return [
            'ok' => false,
            'reply' => 'The AI service is temporarily unavailable. Please try again in a moment.',
        ];
    }

    private function buildUserContext(User $user): string
    {
        $vehicles = $user->vehicles()
            ->select([
                'id',
                'vehicle_number',
                'brand',
                'model',
                'fuel_type',
                'license_expiry',
                'insurance_expiry',
                'emission_test_expiry',
            ])
            ->with(['maintenanceRecords' => function ($query) {
                $query->select([
                    'id',
                    'vehicle_id',
                    'service_type',
                    'service_date',
                    'next_due_date',
                    'mileage',
                    'cost',
                ])->latest('service_date')->limit(5);
            }])
            ->get();

        $lines = [];
        $lines[] = 'User: '.$user->name;
        $lines[] = 'Date Today: '.now()->toDateString();

        if ($user->driver_license_expiry) {
            $lines[] = 'Driver License Expiry: '.$this->formatDateValue($user->driver_license_expiry);
        }

        if ($vehicles->isEmpty()) {
            $lines[] = 'Vehicles: none';
            return implode("\n", $lines);
        }

        $lines[] = 'Vehicle Count: '.$vehicles->count();

        foreach ($vehicles as $index => $vehicle) {
            $prefix = 'Vehicle '.($index + 1).': ';
            $identity = trim(($vehicle->brand ?? '').' '.($vehicle->model ?? ''));
            $identity = $identity !== '' ? $identity : 'Unknown model';

            $lines[] = $prefix.$identity.' ('.$vehicle->vehicle_number.')';
            $lines[] = '- Fuel: '.($vehicle->fuel_type ?: 'Unknown');
            $lines[] = '- License Expiry: '.($vehicle->license_expiry ? $this->formatDateValue($vehicle->license_expiry) : 'Not set');
            $lines[] = '- Insurance Expiry: '.($vehicle->insurance_expiry ? $this->formatDateValue($vehicle->insurance_expiry) : 'Not set');
            $lines[] = '- Emission Test Expiry: '.($vehicle->emission_test_expiry ? $this->formatDateValue($vehicle->emission_test_expiry) : 'Not set');

            $nextService = MaintenanceRecord::query()
                ->where('vehicle_id', $vehicle->id)
                ->whereNotNull('next_due_date')
                ->whereDate('next_due_date', '>=', now()->toDateString())
                ->orderBy('next_due_date')
                ->first();

            $lines[] = '- Next Service: '.($nextService?->next_due_date ? $this->formatDateValue($nextService->next_due_date) : 'Not scheduled');

            if ($vehicle->maintenanceRecords->isEmpty()) {
                $lines[] = '- Recent Maintenance: none';
                continue;
            }

            $lines[] = '- Recent Maintenance Records:';
            foreach ($vehicle->maintenanceRecords as $record) {
                $serviceDate = $record->service_date?->toDateString() ?: 'Unknown date';
                $nextDueDate = $record->next_due_date?->toDateString() ?: 'Not set';
                $lines[] = "  * {$record->service_type} on {$serviceDate}, next due {$nextDueDate}";
            }
        }

        return implode("\n", $lines);
    }

    private function formatDateValue(mixed $value): string
    {
        try {
            return Carbon::parse((string) $value)->toDateString();
        } catch (\Throwable) {
            return 'Unknown';
        }
    }
}
