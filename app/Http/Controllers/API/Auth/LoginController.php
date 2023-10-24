<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Requests\Auth\LoginRequest;

use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\DataTrueResource;

use App\User;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Hash;
use Illuminate\Support\Facades\Crypt;

/*
 |--------------------------------------------------------------------------
 | Login Controller
 |--------------------------------------------------------------------------
 |
 | This controller handles the User login, Change Password and logout Functionality.
 |
 */

class LoginController extends Controller
{

    /**
     * Login user and create token
     * @param LoginRequest $request
     //* @return UsersResource
     //* @return \Illuminate\Http\JsonResponse
     //* @throws \Exception
     */
    public function login(LoginRequest $request)
    {

        dd($request);
        $credentials = array_merge($request->all(), ['user_type' => config('constants.user.user_type_code.admin')]);
        if (!Auth::attempt($credentials))
            return response()->json([
                'error' => config('constants.messages.user.invalid')
            ], config('constants.validation_codes.unprocessable_entity'));
        $user = $request->user();

        if ((isset($user) && $user->status != config('constants.user.status_code.active'))) {
            return response()->json(['error' => config('constants.messages.login.unverified_account')], config('constants.validation_codes.unprocessable_entity'));
        }

        if ($user->user_type != config('constants.user.user_type_code.admin')) {
            return response()->json(['error' => config('constants.messages.user.invalid')], config('constants.validation_codes.unprocessable_entity'));
        }

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        $user->permissions = [];
        if ($user != null) {
            //get User Permission and save permission in token
            $token->scopes = $user->role->permissions->pluck('name')->toArray();
            $token->save();
            $role = Role::findorfail($user->role_id); //get role details
            $user->permissions = Permission::getPermissions($role);
            $user->authorization = $tokenResult->accessToken;
            return new LoginResource($user);
        } else {
            return response("No User found.", config('constants.validation_codes.unverified_account'));
        }
    }

    /**
     * change password functionality.
     *
     * @param ChangePasswordRequest $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        //get all updated data.
        $data = $request->all();
        $masterUser = User::where('email', $request->user()->email)->first();
        if (Hash::check($data['old_password'], $masterUser->password)) {
            $masterData['password'] = bcrypt($data['new_password']);
            //update user password in master user table
            if ($masterUser->update($masterData)) {
                return new DataTrueResource($masterUser);
            } else
                return response()->json(['error' => config("constants.messages.something_wrong")], config('constants.validation_codes.unverified_account'));
        } else {
            return response()->json(['error' => config("constants.messages.invalid_old_password")], config('constants.validation_codes.unverified_account'));
        }
    }

    /**
     * Download File
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadFile(Request $request)
    {
        return response()->download(Crypt::decryptString($request->get('file')));
    }

    /**
     * Logout User
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public static function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json('You have been Successfully logged out!');
    }
}
