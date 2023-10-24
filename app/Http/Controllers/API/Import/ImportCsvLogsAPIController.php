<?php

namespace App\Http\Controllers\API\Import;

use App\User;
use App\Models\Import\Import_csv_log;
use App\Http\Resources\Import\ImportCsvLogsCollection;
use App\Http\Resources\Import\ImportCsvLogsResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;


class ImportCsvLogsAPIController extends Controller
{

    /*
   |--------------------------------------------------------------------------
   | ImportCsvLogs Controller
   |--------------------------------------------------------------------------
   |
   | This controller handles the Roles of
       index,
       show,
   |
   */

    /**
     * List All Users
     * @param Request $request
     * @return ImportCsvLogsCollection
     */
    public function index(Request $request)
    {
        $query = User::commonFunctionMethod(Import_csv_log::class,$request);
        return new ImportCsvLogsCollection(ImportCsvLogsResource::collection($query),ImportCsvLogsResource::class);
    }

    /**
     * import_csv_log detail
     * @param import_csv_log $import_csv_log
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(import_csv_log $import_csv_log)
    {
        $Import_csv_log = Import_csv_log::where('id', $import_csv_log->id)->first();
        return response()->json(['errors' => \GuzzleHttp\json_decode($Import_csv_log->error_log)], config('constants.validation_codes.ok'));
    }

}
