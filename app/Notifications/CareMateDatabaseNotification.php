<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CareMateDatabaseNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $body,
        public ?string $actionUrl = null,
        public string $type = 'general',
        public ?string $actionText = 'View Details'
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database'];

        // Optionally send email if mail driver is configured
        if (config('mail.default') && config('mail.default') !== 'log' && ! empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('CareMate BD: '.$this->title)
            ->greeting('Hello '.($notifiable->name ?? 'there').',')
            ->line($this->body);

        if ($this->actionUrl) {
            $mail->action($this->actionText ?? 'View Details', $this->actionUrl);
        }

        $mail->line('Thank you for choosing CareMate BD — Care that feels like family.');

        return $mail;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'action_url' => $this->actionUrl,
            'action_text' => $this->actionText,
            'type' => $this->type,
        ];
    }
}
