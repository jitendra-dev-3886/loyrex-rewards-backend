<?php

namespace App\Jobs\Product;

use App\Http\Resources\DataTrueResource;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ZipArchive;

class ProductsZipArchiveJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
//        $zip = new ZipArchive;
//        $res = $zip->open($this->file);
//        if ($res === TRUE) {
//            $zip->extractTo(storage_path('app/public/'));
//            $zip->close();
//            return new DataTrueResource(true);
//        } else {
//            return response()->json(['error' =>'failed'], config('constants.validation_codes.unprocessable_entity'));
//        }
    }

    /**
     * Execute the job.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle()
    {
        $zip = new ZipArchive;
        dd($zip);
        $res = $zip->open($this->file);
        if ($res === TRUE) {
            $zip->extractTo(storage_path('app/public/'));
            $zip->close();
            return new DataTrueResource(true);
        } else {
            return response()->json(['error' =>'failed'], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
