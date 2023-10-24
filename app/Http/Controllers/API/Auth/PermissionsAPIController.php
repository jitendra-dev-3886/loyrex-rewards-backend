<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Auth\Permission;
use App\Http\Resources\Auth\PermissionsCollection;
use App\Http\Resources\Auth\PermissionsResource;
use App\Http\Requests\Auth\PermissionsRequest;
use App\Http\Requests\Auth\SetUnsetPermissionToRoleRequest;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\DataTrueResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Auth\PermissionsExport;

class PermissionsAPIController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Permission Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the Permissions of index, show, store, update, destroy, setUnsetPermissionToRole and Export Methods.
    |
    */

    /**
     * Permissions List
     * @param Request $request
     * @return PermissionsCollection
     * @return PermissionsResource
     */

    public function index(Request $request)
    {
        $query = User::commonFunctionMethod(Permission::class,$request);
        return new PermissionsCollection(PermissionsResource::collection($query),PermissionsResource::class);
    }

    /**
     * Permission Detail
     * @param Permission $permission
     * @return PermissionsResource
     */

    public function show(Permission $permission)
    {
        return new PermissionsResource($permission);
    }

    /**
     * Create a new Permission instance after a valid Permission.
     * @param PermissionsRequest $request
     * @return PermissionsResource
     */

    public function store(PermissionsRequest $request)
    {
        return new PermissionsResource(Permission::create($request->all()));
    }

    /**
     * Update Permission
     * @param PermissionsRequest $request
     * @param Permission $permission
     * @return PermissionsResource
     */

    public function update(PermissionsRequest $request, Permission $permission)
    {
        $permission->update($request->all());
        return new PermissionsResource($permission);
    }

    /**
    * Delete Role
    * @param Request $request
    * @param Permission $permission
    * @return DataTrueResource
    */

    public function destroy(Request $request, Permission $permission)
    {
        $permission->delete();
        return new DataTrueResource($permission);
    }

    /**
     * Delete Permission multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Permission::DeleteAll($request);
    }
    /**
     * This method is used set/unset permission to role
     *
     * @param SetUnsetPermissionToRoleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setUnsetPermissionToRole(SetUnsetPermissionToRoleRequest $request)
    {
        return Permission::setUnsetPermission($request);
    }

    /**
     * Export Roles Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new PermissionsExport($request), 'Permissions_' . config('constants.file.name') . '.csv');
    }
}
