<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

/*
    |--------------------------------------------------------------------------
    | Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling verify and resend requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

class VerificationAPIController extends Controller
{

    /**
     * Email Verification
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(Request $request) {
        $user = User::find($request['id']);
        $user->status = config('constants.user.status_code.active');
        $user->save();

        return redirect('');

    }

    /**
     * Resend the email verification notification.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json('User already have verified email!', config('constants.validation_codes.unprocessable_entity'));
        }
        $request->user()->sendEmailVerificationNotification();
        return response()->json('The notification has been resubmitted');
    }
}
