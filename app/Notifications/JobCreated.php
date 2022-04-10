<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class JobCreated extends Notification implements ShouldQueue
{
    use Queueable;

    private string $job_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $job_id)
    {
        $this->job_id = $job_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase()
    {
        return [
            "job_id" => $this->job_id
        ];
    }
}
