<?php

namespace App\Notifications;

use App\Channels\PinnacleEmailChannel;
use App\PinnacleEmail\PinnacleEmailMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PinnacleEmailNotification extends Notification
{
    use Queueable;

    public $email;
    public $subject;
    public $text;
    public $user;
    public $template;
    public $type;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $subject, $text, $user, $template, $type)
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->text = $text;
        $this->user = $user;
        $this->template = $template;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PinnacleEmailChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toPinnacleEmailMessage($notifiable)
    {
        return (new PinnacleEmailMessage())->message($this->user, $this->text, $this->template, $this->type);
    }
}