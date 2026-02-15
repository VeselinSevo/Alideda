<?php
namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Services\ActivityLogger;

class LogLogoutActivity
{
    public function __construct(private ActivityLogger $logger)
    {
    }

    public function handle(Logout $event): void
    {
        $user = $event->user;
        $this->logger->log(
            'user.logout',
            $user ? "User logged out ({$user->email})" : "User logged out",
            $user
        );
    }
}