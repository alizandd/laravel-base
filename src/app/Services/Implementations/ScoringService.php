<?php
namespace App\Services\Implementations;



use App\Models\DailyScoreSummary;
use App\Models\ScoreLog;
use App\Models\User;
use App\Services\Contracts\ScoringActionInterface;
use Illuminate\Support\Facades\Log;

class ScoringService {
    protected $user;
    protected $max_score=200;
    public function __construct(User $user) {
        $this->user = $user;
    }

    public function addScore(ScoringActionInterface $action) {
        $today = now()->startOfDay();
        $dailySummary = DailyScoreSummary::firstOrCreate(
            ['user_id' => $this->user->id, 'date' => $today],
            ['points' => 0]
        );

        $pointsToAdd = $action->score();
        if ($dailySummary->points + $pointsToAdd > $this->max_score) {
            Log::channel('score')->info('Daily score limit reached for user.', [
                'user_id' => $this->user->id,
                'attempted_to_add' => $pointsToAdd,
                'daily_total' => $dailySummary->points,
                'action' => class_basename(get_class($action))
            ]);
            return [
                'success' => false,
                'user_score' =>  $this->user->score, // User's current score without adding new points
                'points_added' => 0, // No points added because the limit was reached
                'daily_total' => $dailySummary->points,
                'action' => class_basename(get_class($action)),
            ];
        }

        $this->user->score += $pointsToAdd;
        $this->user->save();

        $dailySummary->points += $pointsToAdd;
        $dailySummary->save();

        ScoreLog::create([
            'user_id' => $this->user->id,
            'new_score' => $this->user->score,
            'points_added' => $pointsToAdd,
            'daily_total' => $dailySummary->points,
            'action' => class_basename(get_class($action)),
            'date' => $today
        ]);
        Log::channel('score')->info('Score updated for user.', [
            'user_id' => $this->user->id,
            'new_score' => $this->user->score,
            'points_added' => $pointsToAdd,
            'daily_total' => $dailySummary->points,
            'action' => class_basename(get_class($action))
        ]);


        return [
            'success' => true,
            'user_score' => $this->user->score,
            'points_added' => $pointsToAdd,
            'daily_total' => $dailySummary->points,
            'action' => class_basename(get_class($action)),
        ];

    }
}
