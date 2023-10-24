<?php

namespace App\Imports\Voucher;

use App\Models\Product\Product;
use App\Models\User\User;
use App\Models\Voucher\Voucher;
use App\Notifications\PinnacleEmailNotification;
use App\Traits\CreatedbyUpdatedby;
use App\Traits\Scopes;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\HtmlString;
use File;

class VoucherImport implements ToCollection, WithStartRow
{
    use Scopes, CreatedbyUpdatedby;

    private $errors = [];
    private $rows = 0;

    public $email;
    public $authUser;

    public function __construct($email, $authUser)
    {
        $this->email = $email;
        $this->authUser = $authUser;
    }

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
        $todayDate = config('constants.date_format');
        return [
            '0' => 'required | max:191',
            '1' => ['required', Rule::in([0, 1])],
            '2' => ['required', Rule::in([0, 1])],
            '3' => 'nullable|integer|min:1|max:99999|required_if:2,1',
            '4' => 'nullable|integer|required_if:2,0|exists:products,id,deleted_at,NULL',
            '5' => 'nullable',
            '6' => 'required_if:5,NULL,0|max:100',
            '7' => 'nullable|date_format:d-m-Y|after_or_equal:' . $todayDate,
            '8' => 'nullable|regex:/^[6-9]\d{9}$/|digits:10',
            '9' => 'nullable|max:500',
        ];
    }

    public function validationMessages()
    {
        return [
            '0.required' => trans('The name field is required'),
            '0.max' => trans('The name should be max 191'),

            '1.required' => trans('The status field is required'),
            '1.in' => trans('The status field should be either 0 or 1'),

            '2.required' => trans('The voucher type field is required'),
            '2.in' => trans('The voucher type field should be either 0 or 1'),

            '3.integer' => trans('The point field should be integer'),
            '3.required_if' => trans('The point field is required if voucher type is Point'),
            '3.min' => trans('The point must be at least 1'),
            '3.max' => trans('The point must not be greater than 99999'),

            '4.required_if' => trans('The product id field is required if voucher type is Product'),
            '4.exists' => trans('The selected product id is invalid'),

            '5.required' => trans('User ID field is required'),
            '5.integer' => trans('User ID field should be integer'),
            '5.min' => trans('User ID field should be min 0'),

            '6.integer' => trans('No of voucher field should be integer'),
            '6.min' => trans('No of voucher field should be min 1'),
            '6.max' => trans('No of voucher field should be min 100'),
            '6.required_if' => trans('No of voucher field is required if user id is null'),

            '8.regex' => trans('The contact Number is invalid'),
            '8.digits' => trans('The contact number should be 10 digits'),

            '9.max' => trans('Description field should be max 500'),


        ];
    }

    public function validateBulk($collection)
    {
        $i = 1;
        foreach ($collection as $col) {
            $data = $col->toArray();
            $user = 0;
            if ($data[5] != 0 || $data[5] != null)
                $user = User::where('id', $data[5])->where('status', config('constants.user.status_code.active'))->where('deleted_at', null)->first();
            $i++;
            // $errors[$i] = ['row' => $i];

            $validator = Validator::make($col->toArray(), $this->rules(), $this->validationMessages());
            if ($validator->fails() || (is_null($user))) {
                foreach ($validator->errors()->messages() as $messages) {
                    foreach ($messages as $error) {
                        $this->errors[] = $error . ' on row ' . $i;
                    }
                }
                if (is_null($user)) {
                    $this->errors[] = 'Id with user does not exist on row ' . $i;
                }

                // $this->errors[] = (object) $errors[$i];
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
            $voucherIds = [];
            foreach ($collection as $col) {
                if ($col[6] != null || $col[6] != 0 || $col[6] != null) {
                    $reference_voucher_no = Str::random(6);
                    for ($i = 1; $i <= $col[6]; $i++) {
                        $voucher =  Voucher::create([
                            'name' => $col[0],
                            'status'  => (string)$col[1],
                            'voucher_type' => (string)$col[2],
                            'points' => $col[3],
                            'category_id' => $col[4],
                            'user_id' => (string)$col[5],
                            'no_of_vouchers' => '1',
                            'valid_till' => isset($col[7]) ? Carbon::parse($col[7])->format('Y-m-d') : null,
                            'contact_number' => $col[8],
                            'description' => $col[9],
                            'link' => '',
                            'voucher_code' => substr(str_shuffle(Str::random(20)), 0, 10),
                            'reference_voucher_no' => $reference_voucher_no,
                        ]);

                        if ($col[2] == '0') {
                            $link = config('constants.front_user_login_url') . '?voucher_code=' . $voucher['voucher_code'] . '&voucher_type=' . $voucher['voucher_type'] . '&product_id=' . $voucher[5];
                        } else {
                            $link = config('constants.front_user_login_url') . '?voucher_code=' . $voucher['voucher_code'] . '&voucher_type=' . $voucher['voucher_type'] . '&points=' . $voucher['points'];
                        }
                        $voucher->update(['link' => $link]);

                        if (!empty($col[4])) {
                            $product = Product::where('id', $col[4])->first();
                            $voucher->products()->attach($col[4]);
                            $voucher->category_id = $product->category_id;
                            $voucher->save();
                        }
                        if ($col[5] == "0" || $col[5] == null) {
                            $voucherIds[] = $voucher->id;
                        }
                    }
                } else {
                    $voucher =  Voucher::create([
                        'name' => $col[0],
                        'contact_number' => $col[8],
                        'points' => $col[3],
                        'user_id' => (string)$col[5],
                        'link' => url('link?' . Str::random(15)),
                        'voucher_code' => substr(str_shuffle(Str::random(20)), 0, 10),
                        'reference_voucher_no' => Str::random(6),
                        'no_of_vouchers' => '1',
                        'valid_till' => isset($col[7]) ? Carbon::parse($col[7])->format('Y-m-d') : null,
                        'category_id' => $col[4],
                        'description' => $col[9],
                        'voucher_type' => (string)$col[2],
                        'status'  => (string)$col[1],
                    ]);

                    if ($col[2] == '0') {
                        $link = config('constants.front_user_login_url') . '?voucher_code=' . $voucher['voucher_code'] . '&voucher_type=' . $voucher['voucher_type'] . '&product_id=' . $voucher[5];
                    } else {
                        $link = config('constants.front_user_login_url') . '?voucher_code=' . $voucher['voucher_code'] . '&voucher_type=' . $voucher['voucher_type'] . '&points=' . $voucher['points'];
                    }
                    $voucher->update(['link' => $link]);

                    if (!empty($col[4])) {
                        $product = Product::where('id', $col[4])->first();
                        $voucher->products()->attach($col[4]);
                        $voucher->category_id = $product->category_id;
                        $voucher->save();
                    }

                    if ($voucher->user_id != "0") {

                        $user = User::where('id', $voucher->user_id)->first();
                        $email = $user->email;
                        $subject = 'Your voucher code is here!';
                        $template = 'Voucher.SendVoucherDetails';
                        $customerMailText = new HtmlString('Now claim your ' . config('constants.vouchers.voucher_type.' . $voucher->voucher_type) . ' voucher from Loyrex. Your unique voucher code is <b>' . $voucher['voucher_code'] . '</b>. You can claim your voucher by using link ' . $voucher['link'] . '. Hurry and get it!');

                        $user->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User

                    } else {
                        $voucherIds[] = $voucher->id;
                    }
                }

                $this->rows++;
            }

            $emails[] = $this->authUser->email;
            if (!is_null($this->email)) {
                $emails[] = $this->email;
            }

            $this->sendMailForPendingUserIdRecords($voucherIds, $emails);
        }
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function sendMailForPendingUserIdRecords($voucherIds, $emails)
    {
        $vouchers = Voucher::whereIn('id', $voucherIds)->get();

        File::makeDirectory(storage_path('app\importData'), $mode = 0777, true, true);

        $columns = array('Voucher name', 'Voucher status', 'Voucher type', 'Points', 'Product id', 'User id', 'No of voucher', 'Reference voucher no.', 'Link', 'Voucher code', 'Validity(dd-MM-yyyy)', 'Contact number', 'Description');
        $fileName = storage_path('app\importData\Import_Report.csv');

        try {
            $handle = fopen($fileName, 'w');
            fputcsv($handle, $columns);

            foreach ($vouchers as $voucher) {
                $row['Voucher name']  = $voucher->name;
                $row['Voucher status']    = $voucher->status;
                $row['Voucher type']    = $voucher->voucher_type;
                $row['Points']  = $voucher->points;
                $row['Product id']  = (($voucher->products)->isEmpty()) ? '' : $voucher->products()->pluck('id')[0];
                $row['User id']  = $voucher->user_id;
                $row['No of voucher']  = $voucher->no_of_vouchers;
                $row['Reference voucher no.']  = $voucher->no_of_vouchers;
                $row['Link']  = $voucher->link;
                $row['Voucher code']  = $voucher->voucher_code;
                $row['Validity(dd-MM-yyyy)']  = $voucher->valid_till;
                $row['Contact number']  = $voucher->contact_number;
                $row['Description']  = $voucher->description;

                fputcsv($handle, array($row['Voucher name'], $row['Voucher status'], $row['Voucher type'], $row['Points'], $row['Product id'], $row['User id'], $row['No of voucher'], $row['Reference voucher no.'], $row['Link'], $row['Voucher code'], $row['Validity(dd-MM-yyyy)'], $row['Contact number'], $row['Description']));
            }

            fclose($handle);

            $attachmentLink = Voucher::generateAssetsLink($fileName);

            foreach ($emails as $email) {
                $subject = 'Bulk Voucher Details';
                $template = 'Voucher.BulkVoucherDetails';
                $customerMailText = new HtmlString('Please click on below link to find a CSV file for the voucher codes generated in LoyRex solutions.');

                $this->authUser->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $this->authUser, $template, $type = '2', $attachmentLink)); // Email to User
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
