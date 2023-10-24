<?php

namespace App\Http\Controllers\API\Contact;


use App\Exports\Contact\ContactsExport;
use App\Models\Contact\Contact;
use App\Http\Resources\Contact\ContactCollection;
use App\Http\Resources\Contact\ContactResource;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Maatwebsite\Excel\Facades\Excel;


class ContactsAPIController extends Controller
{

    /**
     * @param Request $request
     * @return ContactCollection
     */
    public function index(Request $request)
    {

        $query = User::commonFunctionMethod(Contact::class, $request);
        return new ContactCollection(ContactResource::collection($query), ContactResource::class);
    }

    /**
     * Role Detail
     * @param Contact $contact
     * @return ContactResource
     */

    public function show(Contact $contact)
    {
        return new ContactResource($contact->load([]));
    }


    /**
     * Export Contacts Data
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        Artisan::call('config:cache');
        return Excel::download(new ContactsExport($request), 'Contacts_' . config('constants.file.name') . '.csv');
    }


}
