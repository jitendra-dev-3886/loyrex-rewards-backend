<?php

namespace App\Http\Controllers\API\Voucher;

use App\Exports\Voucher\VoucherExport;
use App\Http\Requests\Import\CsvRequest;
use App\Http\Requests\Voucher\UpdateVoucherRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\Voucher\VouchersCollection;
use App\Http\Resources\Voucher\VouchersResource;
use App\Http\Requests\Voucher\VouchersRequest;
use App\Imports\Voucher\VoucherImport;
use App\Models\Voucher\Voucher;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Voucher\SendVoucherManuallyRequest;
use App\Notifications\PinnacleEmailNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Maatwebsite\Excel\Facades\Excel;

/*
 * Vouchers Controller
 * This controller handles the Roles of
 * index,
 * show,
 * store,
 * update,
 * updatestatus,
 * export Methods.
 */

class VouchersAPIController extends Controller
{

    /**
     * List All Vouchers
     * @param Request $request
     * @return VouchersCollection
     */
    public function index(Request $request)
    {
        $query = User::commonFunctionMethod(Voucher::class, $request);
        return new VouchersCollection(VouchersResource::collection($query), VouchersResource::class);
    }

    /**
     * Vouchers detail
     * @param Voucher $voucher
     * @return VouchersResource
     */

    public function show(Voucher $voucher)
    {
        return new VouchersResource($voucher->load([]));
    }

    /**
     * Create a new Voucher instance after a valid Role.
     * @param VouchersRequest $request
     * @return VouchersResource
     */

    public function store(VouchersRequest $request)
    {
        return Voucher::insertVoucher($request);
    }

    /**
     * Update Voucher
     * @param VouchersRequest $request
     * @param Voucher $voucher
     * @return VouchersResource
     */

    public function update(VouchersRequest $request, Voucher $voucher)
    {
        return Voucher::updateVoucher($request, $voucher);
    }

    /**
     * Delete Voucher
     *
     * @param Request $request
     * @param Voucher $voucher
     * @return DataTrueResource
     * @throws \Exception
     */

    public function destroy(Request $request, Voucher $voucher)
    {
        $voucher->delete();
        return new DataTrueResource($voucher);
    }

    /**
     * Delete Voucher multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Voucher::deleteAll($request);
    }

    /**
     * Export Voucher Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new VoucherExport($request), 'Vouchers_' . config('constants.file.name') . '.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function importBulk(CsvRequest $request)
    {
        return User::importBulk($request, new VoucherImport($request->get('email'), $request->user()), config('constants.models.voucher_model'), config('constants.import_dir_path.voucher_dir_path'));
    }

    /**
     * Update Voucher status
     * @param Request $request
     * @return DataTrueResource
     */
    public function updatestatus(UpdateVoucherRequest $request)
    {
        return Voucher::updateStatus($request);
    }

    public function sendVoucherManually(SendVoucherManuallyRequest $request)
    {
        $data = $request->all();
        $voucher = Voucher::where('id', $data['voucher_id'])->first();
        if (!empty($voucher)) {
            // expired
            if ($voucher->valid_till != null && Carbon::now()->toDateString() > $voucher->valid_till) {
                return response()->json(
                    ['error' => config('constants.messages.voucher.voucher_expired')],
                    config('constants.validation_codes.unprocessable_entity')
                );
            }

            // inactive and redeemed
            if (
                $voucher->status == config('constants.vouchers.status_code.deactive') ||
                $voucher->voucher_redeem == config('constants.vouchers.voucher_redeem_code.yes')
            ) {
                return response()->json(
                    ['error' => config('constants.messages.voucher.voucher_not_exists')],
                    config('constants.validation_codes.unprocessable_entity')
                );
            }

            $user = Auth::user();
            $email = $data['email'];
            $subject = 'Congratulations, your voucher is here!';
            $template = 'Voucher.SendVoucherDetails';

            if (!is_null($voucher->valid_till)) {
                if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                    $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                } else {
                    $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it before ' . $voucher->valid_till . '. You can now visit ' . $voucher['link'] . ' to redeem.');
                }
            } else {
                if ($voucher->voucher_type == config('constants.vouchers.voucher_type_code.points')) {
                    $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->points . ' points. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                } else {
                    $customerMailText = new HtmlString('Congratulations! You are just few steps away to claim your voucher. Now claim your voucher of ' . $voucher->products()->pluck('name')[0] . ' product. Your Unique Discount Voucher code is <b>' . $voucher['voucher_code'] . '</b>. Hurry and get it soon. You can now visit ' . $voucher['link'] . ' to redeem.');
                }
            }

            $voucher->notify(new PinnacleEmailNotification($email, $subject, $customerMailText, $user, $template, $type = '2', null)); // Email to User

            return response()->json(['message' => 'Voucher link sent successfully!', 'data' => $voucher]);
        } else {
            return response()->json(['error' => config('constants.messages.voucher.voucher_not_found')], config('constants.validation_codes.unprocessable_entity'));
        }
    }
}
