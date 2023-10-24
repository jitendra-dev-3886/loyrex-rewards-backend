<?php

namespace App\Http\Controllers\API\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Dashboard\Dashboard;
use App\User;
use App\Models\Voucher\Voucher;
use Carbon\Carbon;

class DashboardsAPIController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = Dashboard::getAllData();
        return response()->json(['data' => $data]);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function totalActiveVouchers()
    {
        $today = Carbon::now()->format('Y-m-d');
        $total_active_vouchers = Voucher::where('status', '1')->whereDate('valid_till', '>=', $today)->get()->count();
        return response()->json(['data' => ['total_active_vouchers' => $total_active_vouchers]]);
    }
}
