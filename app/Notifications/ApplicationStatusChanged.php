<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

/** Sent to a job seeker when the employer changes their application status. */
class ApplicationStatusChanged extends Notification
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
            'icon' => 'clipboard-check',
            'message' => "Your application for {$job->title} is now: ".ucfirst($this->application->status),
            'url' => route('seeker.applications.index'),
        ];
    }
}
