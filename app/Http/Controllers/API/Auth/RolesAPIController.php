<?php

namespace App\Http\Controllers\API\Auth;

use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use App\User;
use App\Http\Requests\Auth\RolesRequest;
use App\Http\Resources\Auth\RolesCollection;
use App\Http\Resources\Auth\RolesResource;
use Illuminate\Http\Request;
use App\Http\Resources\DataTrueResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Auth\RolesExport;

/*
|--------------------------------------------------------------------------
| Roles Controller
|--------------------------------------------------------------------------
|
| This controller handles the Roles of
index,
show,
store,
update,
destroy,
export and
getPermissionsByRole Methods.
|
*/

class RolesAPIController extends Controller
{

    /**
    * Roles List
    * @param Request $request
     * @return RolesCollection
     * @return RolesResource
    */

    public function index(Request $request)
    {
        if($request->get('is_light',false)) {
            $role = new Role();
            $query = User::commonFunctionMethod(Role::select($role->light),$request,true);
        } else {
            $query = User::commonFunctionMethod(Role::class,$request);
        }
        return new RolesCollection(RolesResource::collection($query),RolesResource::class);
    }

    /**
    * Role Detail
    * @param Role $role
    * @return RolesResource
    */

    public function show(Role $role)
    {
        return new RolesResource($role->load([]));
    }

    /**
     * Create a new Role instance after a valid Role.
     * @param RolesRequest $request
     * @return RolesResource
     */

    public function store(RolesRequest $request)
    {
        return new RolesResource(Role::create($request->all()));
    }

    /**
     * Update Role
     * @param RolesRequest $request
     * @param Role $role
     * @return RolesResource
     */

    public function update(RolesRequest $request, Role $role)
    {
        $role->update($request->all());

        return new RolesResource($role);
    }

    /**
     * This method is used to delete role
     *
     * @param Request $request
     * @param Role $role
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Role $role)
    {
        return Role::deleteAPI($request,$role);
    }

    /**
     * Delete Role multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return Role::deleteAll($request);
    }
    /**
     * Export Roles Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new RolesExport($request), 'Roles_' . config('constants.file.name') . '.csv');
    }

    /**
     * Role Detail
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

     public function getPermissionsByRole($id)
     {
         $role = Role::where("id", $id)->with("permissions")->firstorfail();
         $allPermission = Permission::getPermissions($role);
         return response()->json(["message" => "", "data" => $allPermission]);
     }

}
