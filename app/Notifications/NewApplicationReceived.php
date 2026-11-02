<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Sent to an employer when someone applies to one of their jobs. */
class NewApplicationReceived extends Notification
{
    use Queueable;

    public function __construct(public Application $application)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $job = $this->application->jobListing;

        return [
            'icon' => 'person-plus',
            'message' => "{$this->application->user->name} applied for {$job->title}",
            'url' => route('employer.jobs.applications', $job),
        ];
    }
}
