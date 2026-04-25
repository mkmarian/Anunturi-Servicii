<?php

namespace App\Notifications;

use App\Domain\Listings\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ListingRejected extends Notification
{
    use Queueable;

    public function __construct(
        public Listing $listing,
        public ?string $reason = null,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->listing->title;

        $mail = (new MailMessage)
            ->subject('Anuntul tau a fost respins - ' . $title)
            ->greeting('Salut, ' . $notifiable->name . '!')
            ->line('Ne pare rau, anuntul **' . $title . '** nu a putut fi aprobat.');

        if ($this->reason) {
            $mail->line('Motiv: ' . $this->reason);
        }

        return $mail
            ->line('Poti edita anuntul si il poti retrimite spre aprobare.')
            ->action('Anunturile mele', route('craftsman.listings.index'))
            ->line('Daca ai intrebari, contacteaza-ne.')
            ->salutation('Echipa MeseriiRo');
    }
}