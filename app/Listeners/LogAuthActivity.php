<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\ActivityLogger;

class LogAuthActivity
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function handle(Login $event): void
    {
        $this->logger->log(
            'user.login',
            "User logged in ({$event->user->email})",
            $event->user
        );
    }
}