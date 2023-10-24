<?php

namespace App\Http\Controllers\API\Admin;
use App\Exports\Admin\UsersExport;
use App\Http\Requests\Admin\CheckEmailRequest;
use App\Http\Requests\Import\CsvRequest;
use App\Http\Resources\DataTrueResource;
use App\Imports\Admin\UsersImport;
use Carbon\Carbon;
use EddTurtle\DirectUpload\Signature;
use Illuminate\Filesystem\Filesystem;
use App\User;
use App\Http\Requests\Admin\UsersRequest;
use App\Http\Resources\Admin\UsersCollection;
use App\Http\Resources\Admin\UsersResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Artisan;
use URL;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Traits\UploadTrait;
/*
|--------------------------------------------------------------------------
| Users Controller
|--------------------------------------------------------------------------
|
| This controller handles the Roles of
    register,
    checkemail
    index,
    show,
    store,
    update,
    destroy,
    export,
    batchRequest Methods.
|
*/

class UsersAPIController extends Controller
{

    use UploadTrait;


    /***
     * Register New User
     * @param UsersRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(UsersRequest $request)
    {
        return User::Register($request);
    }

    /**
     * List All Users
     * @param Request $request
     * @return UsersCollection
     */
    public function index(Request $request)
    {

        $query = User::commonFunctionMethod(User::class,$request);
        return new UsersCollection(UsersResource::collection($query),UsersResource::class);
    }

    /**
     * check email exists in business or not.
     *
     * @param CheckEmailRequest $request
     * @return DataTrueResource|\Illuminate\Http\JsonResponse
     */
    public function checkEmail(CheckEmailRequest $request)
    {
        $userType = config('constants.user.user_type_code.admin');
        $response = User::codeExist($request, 'email', $userType);
        return response()->json(['data' => $response]);
    }

    /**
     * Users detail
     * @param User $user
     * @return UsersResource
     */
    public function show(User $user)
    {
        return new UsersResource($user->load([]));
    }

    /**
     * Update Users
     * @param UsersRequest $request
     * @param User $user
     * @return UsersResource
     */
    public function update(UsersRequest $request, User $user)
    {
        return User::UpdateUser($request,$user);

    }

    /**
     * Delete User
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Request $request, User $user)
    {
        if($user->id == config('constants.system_user_id')) {
            return User::GetError(config('constants.messages.admin_user_delete_error'));
        } else {
            $user->delete();
            return new DataTrueResource($user);
        }
    }

    /**
     * Delete User multiple
     * @param Request $request
     * @return DataTrueResource
     */
    public function deleteAll(Request $request)
    {
        return User::DeleteAll($request);
    }
    /**
     * Export Users Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new UsersExport($request), 'Admin_users_' . config('constants.file.name') . '.csv');
    }

    /**
     * Import bulk
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function importBulk(CsvRequest $request)
    {
        return User::importBulk($request, new UsersImport(),
            config('constants.models.admin_user_model'),
            config('constants.import_dir_path.admin_user_dir_path'));
    }


    /**
     * This is a batch request API
     *
     * @param Request $requestObj
     * @return \Illuminate\Http\JsonResponse
     */
    public function batchRequest(Request $requestObj)
    {
        $requests  = $requestObj->get('request');//get request
        $output = array();
        $cnt = 0;
        foreach ($requests as $request) {// foreach for all requests inside batch

            $request = (object) $request;// array request convert to object

            if($cnt == 10)// limit maximum call 10 requests
                break;

            $url = parse_url($request->url);

            //querystrings code
            $query = array();
            if (isset($url['query'])) {
                parse_str($url['query'], $query);
            }

            $server = ['HTTP_HOST'=> preg_replace('#^https?://#', '', URL::to('/')), 'HTTPS' => 'on'];
            $req = Request::create($request->url, 'GET', $query, [],[], $server);// set request

            $req->headers->set('Accept', 'application/json');//set accept header
            $res = app()->handle($req);//call request

            if (isset($request->request_id)) {// check request_id is set or not
                $output[$request->request_id] = json_decode($res->getContent()); // get response and set into output array
            } else {
                $output[] = $res;
            }

            $cnt++;// request counter
        }

        return response()->json(array('response' => $output));// return batch response
    }

    /**
     * Get the aws S3 URL for Signer
     */
    public function awsS3UrlForSigner(Request $request)
    {
        $opts = [
            'acl' => 'public-read-write',
            'expires' => Carbon::now()->addDay()->format('Y-m-d H:i:s')
        ];
        $upload = new Signature(
            config('s3.aws_access_key_id'),
            config('s3.aws_secret_access_key'),
            config('s3.aws_bucket'),
            config('s3.aws_default_region'),
            $opts
        );

        $signature = $upload->getFormInputs();
        $signature['key'] = config('s3.aws_temporary_folder_name') . '/' . time() . config('s3.separator') . $signature['key'];

        return response()->json([
            'signature' => $signature,
            'postEndpoint' => $upload->getFormUrl()
        ]);
    }


}
