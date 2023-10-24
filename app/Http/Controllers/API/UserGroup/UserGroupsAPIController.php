<?php

namespace App\Http\Controllers\API\UserGroup;

use App\Exports\UserGroup\UserGroupsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserGroup\UserGroupsRequest;
use App\Http\Resources\UserGroup\UserGroupsCollection;
use App\Http\Resources\UserGroup\UserGroupsResource;
use App\Models\User\User;
use App\Models\UserGroup\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;


class UserGroupsAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return UserGroupsCollection
     */
    public function index(Request $request)
    {
        if($request->get('is_light',false)) {
            $userGroup = new UserGroup();
            $query = User::commonFunctionMethod(UserGroup::select($userGroup->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(UserGroup::class, $request);
        }
        return new UserGroupsCollection(UserGroupsResource::collection($query), UserGroupsResource::class);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserGroupsRequest $request
     * @return UserGroupsResource
     */
    public function store(UserGroupsRequest $request)
    {
        return UserGroup::CreateUserGroup($request);
    }

    /**
     * Display the specified resource.
     *
     * @param UserGroup $userGroup
     * @return UserGroupsResource
     */
    public function show(UserGroup $userGroup)
    {
        return new UserGroupsResource($userGroup->load([]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserGroupsRequest $request
     * @param UserGroup $userGroup
     * @return UserGroupsResource
     */

    public function update(UserGroupsRequest $request, UserGroup $userGroup)
    {
        return UserGroup::UpdateUserGroup($request, $userGroup);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param UserGroup $userGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, UserGroup $userGroup)
    {
        return UserGroup::deleteAPI($request, $userGroup);
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteAll(Request $request)
    {
        return UserGroup::deleteAll($request);
    }
    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new UserGroupsExport($request), 'UserGroups_' . config('constants.file.name') . '.csv');
    }
}
