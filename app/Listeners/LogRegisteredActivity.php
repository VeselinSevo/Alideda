<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Services\ActivityLogger;

class LogRegisteredActivity
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function handle(Registered $event): void
    {
        $this->logger->log(
            'user.register',
            "User registered ({$event->user->email})",
            $event->user
        );
    }
}