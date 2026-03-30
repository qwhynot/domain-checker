<?php

namespace App\Notifications;

use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainRecoveryNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Domain $domain,
        public DomainCheck $check
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('domains.show', $this->domain);
        
        return (new MailMessage)
            ->success()
            ->subject("✅ Domain Recovered: {$this->domain->url}")
            ->greeting("Good News!")
            ->line("Your domain **{$this->domain->url}** is back online!")
            ->line("**Status:** {$this->check->status}")
            ->line("**HTTP Code:** {$this->check->http_status_code}")
            ->line("**Response Time:** {$this->check->response_time_ms} ms")
            ->line("**Checked At:** {$this->check->checked_at->format('Y-m-d H:i:s')}")
            ->action('View Domain Details', $url)
            ->line('Your domain is now operating normally.');
    }
}
