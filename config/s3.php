<?php

use Carbon\Carbon;

return [
    'filesystem_driver' => env('FILESYSTEM_DISK'),
    'aws_access_key_id' => env('S3_ACCESS'),
    'aws_secret_access_key' => env('S3_SECRET'),
    'aws_bucket' => env('S3_BUCKET'),
    'aws_default_region' => env('S3_REGION'),
    'aws_temporary_folder_name' => 'tmp',
    'permission' => 'public-read-write',
    'expiry' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
    'separator' => '$#lyx__',
    'url' => 'https://s3.ap-south-1.amazonaws.com/' . env('S3_BUCKET')
];
