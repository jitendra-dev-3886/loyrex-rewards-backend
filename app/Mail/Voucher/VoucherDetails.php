<?php

namespace App\Mail\Voucher;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VoucherDetails extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $user;
    protected $emails_template;
    protected $link;
    protected $voucher_code;
    public function __construct($user,$link,$voucher_code,$emails_template,$subject)
    {
        $this->user=$user;
        $this->emails_template=$emails_template;
        $this->subject=$subject;
        $this->link=$link;
        $this->voucher_code=$voucher_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->markdown('emails.Voucher.'.$this->emails_template,[ 'link' => $this->link, 'voucher_code'=> $this->voucher_code])->with('user',$this->user);
    }
}
