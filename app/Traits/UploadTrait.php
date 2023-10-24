<?php

namespace App\Traits;

use App\Models\User\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    protected $s3;

    /**
     * Upload in Bucket
     */
    public function uploadInBucket($image, $folder)
    {
        Storage::disk('s3')->makeDirectory($folder);
        $imagePath = $folder . str_replace(config('s3.aws_temporary_folder_name'), '', $image);
        $this->copyUploadFile($image, $imagePath); // copy file from tmp location to our custom path
        return $imagePath;
    }

    /**
     * Upload In Local
     */
    public function uploadInLocal(UploadedFile $uploadedFile, $folder)
    { // $folder = target folder
        $ext = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION); // original file extension
        $file_name = User::getRandomString(40) . '.' . $ext; // generate filename with auto generate name and original file extension
        return $uploadedFile->storeAs($folder, $file_name); // saving method with custom generated filename with original file extension
    }

    public function deleteOne($path)
    {
        if (Storage::disk('s3')->exists(urldecode($path))) {
            Storage::disk('s3')->delete(urldecode($path));
        }
    }

    public function is_file_exists($path)
    {
        if (!is_null($path) && Storage::exists($path)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  Optimize Image
     *
     * @param $uploadedFile
     * @param $realPath
     * @param $width
     * @param $height
     * @return array
     */
    public function resizeImages($image, $realPath)
    {
        $diskName = config('s3.filesystem_driver');

        if ($diskName == 's3') { // In S3
            $path = $this->uploadInBucket($image, $realPath);
        } else { // In local
            $createThumb = public_path('storage/' . $realPath . '/thumbs/');

            if (!File::isDirectory($createThumb)) {
                File::makeDirectory($createThumb, 0755, true, true);
            }

            $path = $this->uploadInLocal($image, $realPath);
            $path = $realPath . '/' . pathinfo($path, PATHINFO_BASENAME);
        }

        $image_array['image'] = $path;
        $image_array['size'] = Storage::size($path);

        return $image_array;
    }

    /**
     * Store in s3 bucket
     */
    public function storeInBucket($path)
    {
        return [
            "service" => "s3",
            "aws_access_key_id" => config('s3.aws_access_key_id'),
            "aws_secret_access_key" => config('s3.aws_secret_access_key'),
            "region" => config('s3.aws_default_region'),
            "headers" => array("Cache-Control" => "max-age=31536000, public"),
            "path" => config('s3.aws_bucket') . '/' . $path
        ];
    }

    /**
     * copy file from one location to another location.
     *
     * @param $from - copy from location
     * @param $to - put file on this location
     */
    public function copyUploadFile($from, $to)
    {
        if (Storage::exists($to)) {
            Storage::delete($to);
        }

        Storage::copy($from, $to);
        Storage::setVisibility($to, 'public');  // set visibility on AWS S3 otherwise return "Access Denied" Error on file access.
    }
}
