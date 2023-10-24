<?php

namespace App\Jobs\Voucher;

use App\Mail\Voucher\VoucherDetails;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class VoucherTypeUserSendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user, $toEmail, $link, $voucher_code;

    /**
     * Create a new job instance.
     *
     * @param User $user
     * @param $toEmail
     * @param $link
     * @param $voucher_code
     */
    public function __construct(User $user, $toEmail, $link, $voucher_code)
    {
        $this->user = $user;
        $this->toEmail = $toEmail;
        $this->link = $link;
        $this->voucher_code = $voucher_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->toEmail)->send(new VoucherDetails($this->user, $this->link, $this->voucher_code,
            'SendVoucherDetails',
            'Loyrex Voucher Details'));
    }
}
