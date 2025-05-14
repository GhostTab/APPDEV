<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrganizerApplicationStatusNotification extends Notification
{
    use Queueable;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $message = $this->status === 'approved' 
            ? 'Your organizer application has been approved! You can now create events.'
            : 'Your organizer application has been rejected.';

        return [
            'status' => $this->status,
            'message' => $message,
            'time' => now()
        ];
    }
}
