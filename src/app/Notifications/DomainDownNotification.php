<?php

namespace App\Notifications;

use App\Models\Domain;
use App\Models\DomainCheck;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DomainDownNotification extends Notification
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
            ->error()
            ->subject("⚠️ Domain Down: {$this->domain->url}")
            ->greeting("Domain Alert!")
            ->line("Your domain **{$this->domain->url}** is currently DOWN.")
            ->line("**Status:** {$this->check->status}")
            ->line("**HTTP Code:** " . ($this->check->http_status_code ?? 'N/A'))
            ->line("**Response Time:** " . ($this->check->response_time_ms ? $this->check->response_time_ms . ' ms' : 'N/A'))
            ->line("**Error:** " . ($this->check->error_message ?? 'Unknown error'))
            ->line("**Checked At:** {$this->check->checked_at->format('Y-m-d H:i:s')}")
            ->action('View Domain Details', $url)
            ->line('Please check your domain configuration.');
    }
}
