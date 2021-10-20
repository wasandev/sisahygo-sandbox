<?php

namespace App\Mail;

use App\Models\Billingnote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BillingnoteSentMail extends Mailable
{
    use Queueable, SerializesModels;
    public $billingnote;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Billingnote $billingnote, $files = [])
    {
        $this->billingnote = $billingnote;
        $this->attachments = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {


        $email =  $this->from('postmaster@mail.sisahygo.online', 'สี่สหายขนส่ง-sisahygo')
            ->to($this->billingnote->ar_customer->email)
            ->subject('เอกสารวางบิล' . 'สำหรับ' . $this->billingnote->ar_customer->name)
            ->markdown('emails.billingnote', ['name' => $this->billingnote->ar_customer->name, 'user' => $this->billingnote->ar_customer->name]);

        foreach ($this->billingnote->billingnote_files as $filePath) {
            //dd(public_path('storage/' . $filePath->billingnote_files));
            $email->attach(public_path('storage/' . $filePath->billingnote_files));
        }
        return $email;
    }
}
