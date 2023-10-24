<?php

namespace App\PinnacleEmail;

class PinnacleEmailMessage
{
    public $user;
    public $text;
    public $template;
    public $type;

    /**
     * if call from ForGotPassword then type is 1 other wise 2
     * @param $orderData
     * @return $this
     */
    public function message($user, $text, $template, $type)
    {

        if ($type == '1') { // type is 1 (ForGotPassword)
            return $template = $template->toHtml();
        } else {
            return view('emails.' . $template, ['text' => $text, 'user' => $user])->render();
        }
    }
}
