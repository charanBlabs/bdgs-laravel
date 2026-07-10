<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function __construct(private ActivityLogService $activityLog) {}

    public function created(Model $model): void
    {
        if ($this->shouldLog($model)) {
            $this->activityLog->log('created', $model);
        }
    }

    public function updated(Model $model): void
    {
        if ($this->shouldLog($model)) {
            $this->activityLog->log('updated', $model, [
                'changes' => $model->getChanges(),
            ]);
        }
    }

    public function deleted(Model $model): void
    {
        if ($this->shouldLog($model)) {
            $this->activityLog->log('deleted', $model);
        }
    }

    private function shouldLog(Model $model): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->isAdmin();
    }
}
