<?php

namespace App\Notifications;

use App\Models\JobListing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/** Sent to a job seeker when a new job matches one of their job alerts. */
class NewJobMatchesAlert extends Notification
{
    use Queueable;

    public function __construct(public JobListing $job)
    {
    }

    public function via(object $notifiable): array
    {
        // "mail" uses MAIL_MAILER from .env (default "log" → written to storage/logs/laravel.log)
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Job Alert: '.$this->job->title)
            ->greeting('Hi '.$notifiable->name.',')
            ->line("A new job matching your alert was just posted: {$this->job->title} at {$this->job->company_name} ({$this->job->location}).")
            ->action('View Job', route('jobs.show', $this->job))
            ->line('Good luck with your application!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'icon' => 'bell',
            'message' => "New job for you: {$this->job->title} at {$this->job->company_name}",
            'url' => route('jobs.show', $this->job),
        ];
    }
}
