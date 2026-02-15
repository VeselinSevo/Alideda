<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'q' => ['nullable', 'string', 'max:255'],
        ]);

        $from = $validated['from'] ?? null;
        $to = $validated['to'] ?? null;
        $q = trim((string) ($validated['q'] ?? ''));

        // custom check: from must be <= to
        if ($from && $to && $from > $to) {
            return back()
                ->withErrors(['to' => 'The "To" date must be the same or after the "From" date.'])
                ->withInput();
        }

        $logs = ActivityLog::query()
            ->with('user')
            ->when($from, fn($qb) => $qb->whereDate('created_at', '>=', $from))
            ->when($to, fn($qb) => $qb->whereDate('created_at', '<=', $to))
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where(function ($w) use ($q) {
                    $w->where('message', 'like', "%{$q}%")
                        ->orWhere('action', 'like', "%{$q}%")
                        ->orWhereHas('user', fn($u) => $u->where('email', 'like', "%{$q}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.activity-logs.index', compact('logs', 'from', 'to', 'q'));
    }
}