<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\AnonymousNotifiable;

class BillingnoteTomail extends Notification
{
    use Queueable;


    protected $billingnote;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($billingnote)
    {
        $this->billingnote = $billingnote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        //$url = url('/app/resources/billingnotes/' . $this->billingnote->id);
        $address = $notifiable instanceof AnonymousNotifiable
            ? $notifiable->routeNotificationFor('mail')
            : $notifiable->ar_customer->email;

        $name  = $this->billingnote->ar_customer->name;
        $user = $this->billingnote->user->name;

        foreach ($this->billingnote->billingnote_files as $attachfile) {
            $attachfile  = Storage::get($attachfile->billingnote_files);
        }
        $mailmessage = (new MailMessage)
            ->from('postmaster@mail.sisahygo.online', 'สี่สหายขนส่ง-sisahygo')
            ->to($address)
            ->subject('เอกสารวางบิล' . ' ' . $name)
            ->markdown('emails.billingnote', ['name' => $name, 'user' => $user])
            //->attach($attachfile)
            ->error();
        dd($mailmessage);
        return $mailmessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
