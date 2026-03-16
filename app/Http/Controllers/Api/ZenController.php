<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Habit\ListHabitsRequest;
use App\Http\Requests\Habit\StoreHabitRequest;
use App\Http\Requests\Habit\UpdateHabitRequest;
use App\Models\Habit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZenController extends Controller
{
    public function index(ListHabitsRequest $request): JsonResponse
    {
        $active = $request->query('active');

        $habits = $request->user()
            ->habits()
            ->when($request->filled('active'), function ($query) use ($active) {
                $query->where('is_active', filter_var($active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false);
            }, function ($query) {
                $query->where('is_active', true);
            })
            ->orderByDesc('id')
            ->get();

        return $this->okResponse($habits, 'Liste des habitudes récupérée');
    }

    public function store(StoreHabitRequest $request): JsonResponse
    {
        $payload = $request->validated();
        $payload['user_id'] = $request->user()->id;

        $habit = Habit::create($payload);

        return $this->okResponse($habit, 'Habitude créée', 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        return $this->okResponse($habit->load('logs'), 'Détail de l\'habitude');
    }

    public function update(UpdateHabitRequest $request, string $id): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $habit->update($request->validated());

        return $this->okResponse($habit, 'Habitude modifiée');
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $habit->delete();

        return $this->okResponse(null, 'Habitude supprimée');
    }
}
