<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function log(
        string $action,
        string $message,
        $user = null,
        $subject = null,
        ?Request $request = null
    ): void {
        $request ??= request();

        ActivityLog::create([
            'user_id' => $user?->id,
            'action' => $action,
            'message' => $message,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->id,
            'ip' => $request?->ip(),
            'user_agent' => substr((string) $request?->userAgent(), 0, 255),
        ]);
    }
}