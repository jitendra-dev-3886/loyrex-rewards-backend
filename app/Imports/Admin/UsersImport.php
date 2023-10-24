<?php

namespace App\Imports\Admin;

use App\Notifications\PinnacleEmailNotification;
use App\Traits\CreatedbyUpdatedby;
use App\User;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithStartRow
{
    use Scopes, CreatedbyUpdatedby;

    private $errors = [];
    private $rows = 0;

    public function startRow(): int
    {
        return 2; // fetch record from second row at import bulk order time.
    }

    public function getErrors()
    {
        return $this->errors; // return all errors
    }

    public function rules(): array
    {
        return [
            '0' => 'required | max:191',
            '1' => 'required | max:191',
            '2' => 'required|integer|exists:roles,id,deleted_at,NULL',
            '3' => 'required | digits:10',
            '4' => 'required|max:191|unique:users,email,NULL,id,deleted_at,NULL',
            '5' => 'required |nullable| min:6 | max:255',
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required' => trans('First Name is required'),
            '1.required' => trans('Last Name is required'),
            '2.required' => trans('Role id is required'),
            '3.required' => trans('Contact Number is required'),
            '4.required' => trans('Email Id is required'),
            '5.required' => trans('Password is required'),
        ];
    }

    public function validateBulk($collection)
    {
        $i = 1;
        foreach ($collection as $col) {
            $i++;
            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error . ' on row ' . $i;
                    }
                }
            }
        }
        return $this->getErrors();
    }

    public function collection(Collection $collection)
    {

        $error = $this->validateBulk($collection);
        if ($error) {
            return;
        } else {
            foreach ($collection as $col) {
                $user =  User::create([
                    'first_name' => $col[0],
                    'last_name' => $col[1],
                    'role_id' => (string)$col[2],
                    'contact_number' => (string)$col[3],
                    'email' => (string)$col[4],
                    'password' => bcrypt($col[5]),
                    'user_type' => config('constants.user.user_type_code.admin'),
                    'status'  => config('constants.user.status_code.active'),
                ]);

                $subject = 'Your new Loyrex account is ready!';
                $email = $user->email;
                $template = 'User.UserRegister';
                $customerMailText = 'Hi ' . $user->first_name . ', Congratulations! Your registration on the Loyrex website has been taken into account. You can now log in to the account and place your order. Click here ' . env('APP_URL') . ' to login.';

                $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User
                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
