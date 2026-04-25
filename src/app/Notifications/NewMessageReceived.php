<?php

namespace App\Notifications;

use App\Domain\Messaging\Models\Conversation;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewMessageReceived extends Notification
{
    use Queueable;

    public function __construct(
        public Conversation $conversation,
        public User $sender,
        public string $preview,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->conversation->listing) {
            $ctx = 'despre "' . $this->conversation->listing->title . '"';
        } elseif ($this->conversation->serviceRequest) {
            $ctx = 'despre cererea "' . $this->conversation->serviceRequest->title . '"';
        } else {
            $ctx = '';
        }

        $subject = $ctx ? ('Mesaj nou ' . $ctx) : 'Ai primit un mesaj nou';
        $preview = Str::limit($this->preview, 200);

        return (new MailMessage)
            ->subject($subject . ' | MeseriiRo')
            ->greeting('Salut, ' . $notifiable->name . '!')
            ->line($this->sender->name . ' ti-a trimis un mesaj nou:')
            ->line('> ' . $preview)
            ->action('Citeste si raspunde', route('messages.show', $this->conversation))
            ->line('Nu raspunde la acest email automat.')
            ->salutation('Echipa MeseriiRo');
    }
}