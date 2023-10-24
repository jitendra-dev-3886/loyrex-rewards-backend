<?php

namespace App\Imports\User;

use App\Models\PointHistory\PointHistory;
use App\Models\User\User;
use App\Notifications\PinnacleEmailNotification;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;

class UsersImport implements ToCollection, WithStartRow, WithHeadingRow
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

    public function rules($col): array
    {
        $id = ((int)$col['user_id'] == 0) ? 'NULL' : (int)$col['user_id'];
        $reward_points_rule_type = ((int)$col['user_id'] == 0) ? 'required' : 'nullable';
        $user_type = config('constants.user.user_type_code.user');

        return [
            'user_id' => 'nullable | integer | exists:users,id,deleted_at,NULL,user_type,' . $user_type,
            'first_name' => 'required | regex:/^[a-zA-Z_ ]*$/ | max:191',
            'last_name' => 'required | regex:/^[a-zA-Z_ ]*$/ | max:191',
            'job_title' => 'nullable | max:191',
            'contact_number' => 'required | regex:/^[6-9]\d{9}$/| unique:users,contact_number,' . $id . ',id,deleted_at,NULL,user_type,' . $user_type,
            'email_id' => 'required | max:191 | email | unique:users,email,' . $id . ',id,deleted_at,NULL,user_type,' . $user_type,
            'reward_points' => $reward_points_rule_type . ' | digits_between:1,6',
            'group_id' => 'required',
            'debit_0_credit_1' => ['nullable', Rule::in([0, 1])]
        ];
    }

    public function validationMessages()
    {
        return [
            'user_id.integer' => trans('ID should be integer'),
            'user_id.exists' => trans('ID is not exists in orders'),

            'first_name.required' => trans('First name is required'),
            'first_name.regex' => trans('First name is invalid'),
            'first_name.max' => trans('First name contains only 191 characters'),

            'last_name.required' => trans('Last name is required'),
            'last_name.regex' => trans('Last name is invalid'),
            'last_name.max' => trans('Last name contains only 191 characters'),

            'job_title.max' => trans('Job title contains only 191 characters'),

            'contact_number.required' => trans('Contact no is required'),
            'contact_number.regex' => trans('Contact is invalid'),
            'contact_number.unique' => trans('Contact Number already exists'),

            'email_id.required' => trans('Email is required'),
            'email_id.email' => trans('Email is invalid'),
            'email_id.max' => trans('Email contains only 191 characters'),
            'email_id.unique' => trans('Email already exists'),

            'reward_points.required' => trans('Reward points is required'),
            'reward_points.digits_between' => trans('The reward points should maximum 6 digits.'),

            'debit_0_credit_1.in' => trans('Debit should be in 0 and Credit should be in 1'),

        ];
    }

    public function validateBulk($collection)
    {
        $i = 1;
        $emails = [];
        $contacts = [];

        $keys = ['user_id', 'first_name', 'last_name', 'job_title', 'contact_number', 'email_id', 'reward_points', 'group_id', 'debit_0_credit_1'];

        foreach ($collection as $col) {

            if (count(array_intersect_key(array_flip($keys), $col->toArray())) !== count($keys)) {
                $this->errors[] = 'Invalid file format, Please download sample file.';
                break;
            }

            if ($col['user_id'] != null) {
                $user = User::where(['id' => $col['user_id'], 'user_type' => config('constants.user.user_type_code.user')])->first();
                if ($user != null) {
                    if ((trim($col['debit_0_credit_1']) != null) && ($col['debit_0_credit_1'] == 0) && ($col['reward_points'] > $user['reward_points'])) {
                        $this->errors[] = 'Reward points should be less than ' . $user['reward_points'] . ' on row ' . $i;
                    }
                } else {
                    $this->errors[] = 'No user exists with ' . $col['user_id'] . ' ID on row ' . $i;
                }
            }

            $contacts[] = $col['contact_number'];
            $emails[] = $col['email_id'];
            $i++;
            $validator = Validator::make($col->toArray(), $this->rules($col), $this->validationMessages());
            if ($validator->fails()) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error . ' on row ' . $i;
                    }
                }
            }
            $group_ids = explode(",", $col['group_id']);

            $db_ids = DB::table('user_groups')->whereNotNull('deleted_at')->get()->pluck('id')->toArray();
            $deletedIds = array_intersect($group_ids, $db_ids);
            foreach ($deletedIds as $key => $value) {
                if (!empty($value)) {
                    $this->errors[] = 'The UserGroup id ' . $value . ' should exists in user_groups table on row ' . $i;
                }
            }

            // Checks each user group id with comma separate value if not available then produce error
            $db_ids = DB::table('user_groups')->select('id')->get()->pluck('id')->toArray();
            $userGroup_diff = array_filter(array_diff($group_ids, $db_ids));

            if (!empty($userGroup_diff)) {
                $this->errors[] = 'No user group id found on row ' . $i;
            }
        }

        // duplicate email with case sensitive in excel file
        $emails_filter = array_filter($emails);
        $emails = array_map('strtolower', $emails_filter); // lower case
        $count_emails = count($emails) != count(array_unique($emails)); // all count != unique count
        if ($count_emails) {
            $this->errors[] = 'Duplicate emails in excel sheet';
        }

        // duplicate contact in excel file
        $contacts_filter = array_filter($contacts);
        $count_contacts = count(array_filter($contacts_filter)) != count(array_unique($contacts_filter)); // all count != unique count
        if ($count_contacts) {
            $this->errors[] = 'Duplicate contacts in excel sheet';
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
                if ($col['user_id'] == null) {
                    // insert
                    $user = User::create([
                        'first_name' => $col['first_name'],
                        'last_name' => $col['last_name'],
                        'job_title' => $col['job_title'],
                        'contact_number' => $col['contact_number'],
                        'email' => (string)$col['email_id'],
                        'password' => bcrypt('123456'),
                        'reward_points' => $col['reward_points'],
                        'user_type' => config('constants.user.user_type_code.user'),
                        'status' => config('constants.user.status_code.active'),
                        'created_by' => Auth::guard('api')->user()->id,
                        'updated_by' => Auth::guard('api')->user()->id,
                    ]);

                    $group_ids = array_filter(explode(",", $col['group_id']));
                    if (!empty($group_ids)) {
                        $user->userGroup()->attach($group_ids);
                    }

                    if ($user->reward_points > 0) {
                        PointHistory::create([
                            'user_id' => $user->id,
                            'action_type' => config('constants.user.action_type.credit'),
                            'points' => $user->reward_points,
                            'description' => config('constants.user.action_type_text.1'),
                            'created_by' => Auth::guard('api')->user()->id,
                            'updated_by' => Auth::guard('api')->user()->id,
                        ]);
                    }
                    $subject = 'Your new Loyrex account is ready!';
                    $email = $user->email;
                    $template = 'User.UserRegister';
                    $customerMailText = 'Hi ' . $user->first_name . ', Congratulations! Your registration on the Loyrex website has been taken into account. You can now log in to the account and place your order. Click here ' . config('constants.front_user_login_url') . ' to login.';

                    $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User
                } else {
                    // update
                    $user = User::find((int)$col['user_id']);

                    if ($col['debit_0_credit_1'] == '0') {
                        // minus
                        $reward_points = $user->reward_points - $col['reward_points'];
                    } else if ($col['debit_0_credit_1'] == '1') {
                        // plus
                        $reward_points = $user->reward_points + $col['reward_points'];
                    } else {
                        // no change
                        $reward_points = $user->reward_points;
                    }

                    $user->update([
                        'first_name' => $col['first_name'],
                        'last_name' => $col['last_name'],
                        'job_title' => $col['job_title'],
                        'reward_points' => $reward_points,
                        'contact_number' => $col['contact_number'],
                        'email' => (string)$col['email_id'],
                        'updated_by' => Auth::guard('api')->user()->id,
                    ]);

                    $group_ids = array_filter(explode(",", $col['group_id']));
                    if (!empty($group_ids)) {
                        $user->userGroup()->detach();
                        $user->userGroup()->attach($group_ids);
                    }

                    PointHistory::create([
                        'user_id' => $user->id,
                        'action_type' => config('constants.user.action_type.credit'),
                        'points' => $col['reward_points'],
                        'description' => config('constants.user.action_type_text.1'),
                        'created_by' => Auth::guard('api')->user()->id,
                        'updated_by' => Auth::guard('api')->user()->id,
                    ]);
                }

                $this->rows++;
            }
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
