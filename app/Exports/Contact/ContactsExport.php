<?php

namespace App\Exports\Contact;

use App\Models\Category\Category;
use App\Models\Contact\Contact;
use App\Models\User\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ContactsExport implements FromCollection, WithHeadings
{
    protected $request;// defined private $request variable

    public function __construct($request)// constructor method
    {
        $this->request = $request;// assign $request $this variable
    }

    /**
     * @return Collection
     */
    public function collection()
    {
        if($this->request->sort == "" && $this->request->order_by == "") {
            return User::commonFunctionMethod(Contact::select('id',
                'first_name',
                'last_name',
                'email',
                'subject',
                'message',
               )->latest()->orderBy('id', 'desc'), $this->request, true, null, null, true);
        } else {
            return User::commonFunctionMethod(Contact::select('id',
                'first_name',
                'last_name',
                'email',
                'subject',
                'message'), $this->request, true, null, null, true);
        }
    }

    public function headings():array
    {
        return ['ID', 'First name', 'Last name', 'Email', 'Subject', 'Message'];
    }
}
