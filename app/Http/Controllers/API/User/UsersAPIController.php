<?php

namespace App\Http\Controllers\API\User;

use App\Exports\User\UsersExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Import\CsvRequest;
use App\Http\Requests\User\CheckContactRequest;
use App\Http\Requests\User\CheckEmailRequest;
use App\Http\Requests\User\FrontUsersRequest;
use App\Http\Resources\DataTrueResource;
use App\Http\Resources\User\UsersCollection;
use App\Http\Resources\User\UsersResource;
use App\Http\Resources\User\UsersShowResource;
use App\Imports\User\UsersImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\User\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use EddTurtle\DirectUpload\Signature;

class UsersAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UsersCollection
     */
    public function index(Request $request)
    {
        if ($request->get('is_light', false)) {
            $user = new User();
            $query = User::commonFunctionMethod(User::select($user->light), $request, true);
        } else {
            $query = User::commonFunctionMethod(User::class, $request);
        }
        return new UsersCollection(UsersResource::collection($query), UsersResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FrontUsersRequest $request
     * @return Response
     */
    public function store(FrontUsersRequest $request)
    {
        return User::CreateUser($request);
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return UsersShowResource
     */
    public function show(User $user)
    {
        return new UsersShowResource($user->load([]));
    }

    /**
     * Update Users
     * @param FrontUsersRequest $request
     * @param User $user
     * @return UsersResource
     */
    public function update(FrontUsersRequest $request, User $user)
    {
        return User::UpdateUser($request, $user);
    }

    /**
     * Delete User
     * @param Request $request
     * @param User $user
     * @return DataTrueResource
     * @throws \Exception
     */
    public function destroy(Request $request, User $user)
    {
        $user->userGroup()->detach();
        $user->delete();

        return new DataTrueResource($user);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        if (!empty($request->id)) {
            $users = User::whereIn('id', $request->id)->get();

            foreach ($users as $user) {
                $user->userGroup()->detach();
                $user->delete();
            }
            return response()->json(['success' => config('constants.messages.delete_success')], config('constants.validation_codes.ok'));
        } else {
            return response()->json([
                'error' => config('constants.messages.delete_multiple_error')
            ], config('constants.validation_codes.unprocessable_entity'));
        }
    }

    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new UsersExport($request), 'Users_' . config('constants.file.name') . '.csv');
    }

    /**
     * check email exists in business or not.
     *
     * @param CheckEmailRequest $request
     * @return DataTrueResource|JsonResponse
     */
    public function checkEmail(CheckEmailRequest $request)
    {
        $userType = config('constants.user.user_type_code.user');
        $response = User::codeExist($request, 'email', $userType);
        return response()->json(['data' => $response]);
    }

    /**
     * check email exists in business or not.
     *
     * @param CheckContactRequest $request
     * @return DataTrueResource|JsonResponse
     */
    public function checkContact(CheckContactRequest $request)
    {
        $userType = config('constants.user.user_type_code.user');
        $response = User::codeExist($request, 'contact_number', $userType);
        return response()->json(['data' => $response]);
    }

    /**
     * Import bulk
     * @param CsvRequest $request
     * @return JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return User::importBulk(
            $request,
            new UsersImport(),
            config('constants.models.user_user_model'),
            config('constants.import_dir_path.user_user_dir_path')
        );
    }
}
