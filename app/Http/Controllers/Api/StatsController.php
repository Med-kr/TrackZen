<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StatsController extends Controller
{
    public function habit(string $id, Request $request): JsonResponse
    {
        $habit = $request->user()->habits()->find($id);

        if (!$habit) {
            return $this->failResponse([
                'habit' => ['Habitude introuvable'],
            ], 'Habitude introuvable', 404);
        }

        $today = Carbon::today();
        $dates = $habit->logs()->orderByDesc('completed_at')->pluck('completed_at')->map(fn (mixed $date) => (string) Carbon::parse($date)->toDateString());
        $uniqueDates = $dates->unique()->values();

        $currentStreak = $this->buildCurrentStreak($uniqueDates, $today);
        $longestStreak = $this->buildLongestStreak($uniqueDates);
        $totalCompletions = $habit->logs()->count();
        $windowStart = $today->copy()->subDays(29);
        $recentCompletions = $habit->logs()->whereDate('completed_at', '>=', $windowStart)->count();
        $completionRate = round(($recentCompletions / 30) * 100, 2);

        return $this->okResponse([
            'habit_id' => $habit->id,
            'current_streak' => $currentStreak,
            'longest_streak' => $longestStreak,
            'total_completions' => $totalCompletions,
            'completion_rate' => $completionRate,
        ], 'Statistiques de l\'habitude');
    }

    public function overview(Request $request): JsonResponse
    {
        $today = Carbon::today();
        $user = $request->user();

        $activeHabits = $user->habits()->where('is_active', true)->get();
        $totalActive = $activeHabits->count();
        $completedToday = HabitLog::query()
            ->whereHas('habit', fn ($query) => $query->where('user_id', $user->id)->where('is_active', true))
            ->whereDate('completed_at', $today)
            ->count();

        $streakBest = null;
        $bestStreak = 0;

        foreach ($activeHabits as $habit) {
            $dates = $habit->logs()->orderByDesc('completed_at')->pluck('completed_at')->map(fn (mixed $date) => (string) Carbon::parse($date)->toDateString());
            $uniqueDates = $dates->unique()->values();
            $current = $this->buildLongestStreak($uniqueDates);

            if ($current > $bestStreak) {
                $bestStreak = $current;
                $streakBest = [
                    'habit_id' => $habit->id,
                    'title' => $habit->title,
                    'streak' => $current,
                ];
            }
        }

        $windowStart = $today->copy()->subDays(6);
        $recentCompletions = HabitLog::query()
            ->whereHas('habit', fn ($query) => $query->where('user_id', $user->id)->where('is_active', true))
            ->whereDate('completed_at', '>=', $windowStart)
            ->count();

        $globalCompletionRate = $totalActive > 0 ? round(($recentCompletions / ($totalActive * 7)) * 100, 2) : 0;

        return $this->okResponse([
            'total_active_habits' => $totalActive,
            'completed_today' => $completedToday,
            'top_streak_active_habit' => $streakBest,
            'global_completion_rate_7d' => $globalCompletionRate,
        ], 'Vue d\'ensemble des statistiques');
    }

    private function buildCurrentStreak(Collection $dates, Carbon $today): int
    {
        $cursor = $today->copy();
        $dateSet = $dates->flip();
        $streak = 0;

        while ($dateSet->has($cursor->toDateString())) {
            $streak++;
            $cursor->subDay();
        }

        return $streak;
    }

    private function buildLongestStreak(Collection $dates): int
    {
        if ($dates->isEmpty()) {
            return 0;
        }

        $sorted = $dates->map(fn ($value) => Carbon::parse($value))->sort()->values();
        $best = 1;
        $current = 1;

        for ($i = 1; $i < $sorted->count(); $i++) {
            $prev = $sorted->get($i - 1);
            $currentDate = $sorted->get($i);

            if ($prev->copy()->addDay()->equalTo($currentDate)) {
                $current++;
            } else {
                $best = max($best, $current);
                $current = 1;
            }
        }

        return max($best, $current);
    }
}
