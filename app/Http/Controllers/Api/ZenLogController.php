<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\HabitLog\StoreHabitLogRequest;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ZenLogController extends Controller
{
    public function index(Request $request, string $id): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $logs = $habit->logs()->orderByDesc('completed_at')->get();

        return $this->okResponse($logs, 'Historique des logs');
    }

    public function store(StoreHabitLogRequest $request, string $id): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $payload = $request->validated();
        $completedAt = $payload['completed_at'] ?? Carbon::today()->toDateString();

        $exists = $habit->logs()->whereDate('completed_at', $completedAt)->exists();

        if ($exists) {
            return $this->failResponse([
                'completed_at' => ['Cette habitude a déjà été enregistrée pour cette date'],
            ], 'Journal déjà existant pour cette date', 422);
        }

        $log = $habit->logs()->create([
            'completed_at' => $completedAt,
            'note' => $payload['note'] ?? null,
        ]);

        return $this->okResponse($log, 'Log créé', 201);
    }

    public function destroy(Request $request, string $id, string $logId): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $log = $habit->logs()->find($logId);

        if (!$log) {
            return $this->failResponse([
                'log' => ['Log introuvable'],
            ], 'Log introuvable', 404);
        }

        $log->delete();

        return $this->okResponse(null, 'Log supprimé');
    }
}
