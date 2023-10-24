<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Notifications\PinnacleEmailNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/*
   |--------------------------------------------------------------------------
   | Password Reset Controller
   |--------------------------------------------------------------------------
   |
   | This controller is responsible for handling password reset emails and
   | includes a trait which assists in sending these notifications from
   | your application to your users. Feel free to explore this trait.
   |
   */

class ForgotPasswordAPIController extends Controller
{
    /**
     * API Forgot Password.
     *
     * @param ForgotPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(ForgotPasswordRequest $request)
    {
        $user = User::where('email', $request->get('email'))->firstOrFail();

        $email = $user->email;
        $token = Str::random(60);

        DB::table('password_resets')->insert(
            [
                'email'      => $email,
                'token'      => bcrypt($token),
                'created_at' => Carbon::now()
            ]
        );

        $subject = 'Reset Password Notification';
        $body = $this->resetPasswordHtml(url(route('password.reset', [
            'token' => $token,
            'email' => $email,
        ], false)));

        $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText = '', $user, $body, $type = '1', null)); // Send Email to user

        return response()->json(['message' => config('constants.messages.forgotpassword_success')]);
    }

    public function resetPasswordHtml($url)
    {
        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'))
            ->render();
    }
}
