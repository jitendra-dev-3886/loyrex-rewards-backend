<?php

namespace App\EZeeSMS;

class EZeeMessage
{
    public $message;
    public $dltTempID;

    /**
     * @param $message
     * @return $this
     */
    public function message($message, $dltTempID)
    {
        $this->message = $message;
        $this->dltTempID = $dltTempID;

        return $this;
    }
}
