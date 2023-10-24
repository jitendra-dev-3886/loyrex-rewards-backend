<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class VaporFileSize implements Rule
{
    protected $size;

    /**
     * Create a new rule instance.
     *
     * @param int $sizeInMb - default 2mb
     * @return void
     */
    public function __construct($sizeInMb = 0)
    {
        $this->size = $sizeInMb;
        if ($this->size == 0)
            $this->size = config('constants.default_single_filesize');
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Storage::exists($value)) {
            $size = Storage::size($value);

            // https://www.gbmb.org/mb-to-bytes
            return $size <= ($this->size * 1048576);     // 1048576 byte = 1MB
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute size must be a less then or equal to ' . config('constants.default_single_filesize') . 'mb';
    }
}
