<?php

namespace App\Services;

use App\Models\BdgsActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        string $action,
        ?Model $subject = null,
        ?array $properties = null,
        ?int $userId = null,
        ?Request $request = null,
    ): BdgsActivityLog {
        $request ??= request();

        return BdgsActivityLog::query()->create([
            'user_id' => $userId ?? $request?->user()?->id,
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent() ? substr($request->userAgent(), 0, 500) : null,
        ]);
    }
}
