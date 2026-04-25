<?php

namespace App\Notifications;

use App\Domain\Listings\Models\Listing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ListingApproved extends Notification
{
    use Queueable;

    public function __construct(public Listing $listing) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->listing->title;
        $slug  = $this->listing->slug;

        return (new MailMessage)
            ->subject('Anuntul tau a fost aprobat - ' . $title)
            ->greeting('Salut, ' . $notifiable->name . '!')
            ->line('Anuntul **' . $title . '** a fost aprobat si este acum vizibil pe MeseriiRo.')
            ->action('Vezi anuntul', route('listings.show', $slug))
            ->line('Multumim ca faci parte din comunitatea MeseriiRo!')
            ->salutation('Echipa MeseriiRo');
    }
}