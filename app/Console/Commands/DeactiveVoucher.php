<?php

namespace App\Console\Commands;


use App\Models\Voucher\Voucher;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DeactiveVoucher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deactive:voucher';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactive Voucher';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('Calling Deactive Voucher Cron Job : '.time());

        $vouchers = Voucher::where('status',config('constants.vouchers.status_code.active'))->whereNotNull('valid_till')->get();
        foreach ($vouchers as $voucher){
            $valid_till = Carbon::parse($voucher->valid_till)->format('Y-m-d');
            $now = Carbon::now()->format('Y-m-d');
            if ($now > $valid_till) {
                //Update Voucher status to '0' Deactive
                Voucher::where('id',$voucher->id)->update(array('status' => config('constants.vouchers.status_code.deactive')));
                Log::debug('Calling Deactive Voucher Id : '.$voucher->id);
            }
        }
    }
}
