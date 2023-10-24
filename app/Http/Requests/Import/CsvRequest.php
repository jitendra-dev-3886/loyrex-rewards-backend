<?php

namespace App\Http\Requests\Import;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class CsvRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        $path = $request->path();
        if ($path == 'api/v1/vouchers-bulk') {
            return [
                'file' => 'required|mimes:csv,txt|max:5120',
                'email' => 'nullable|max:191|email'
            ];
        } else {
            return [
                'file' => 'required|mimes:csv,txt|max:5120'
            ];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'file.required' => 'The file field is required.',
            'file.mimes' => 'The file field should only be csv format.',
            'file.max' => 'The file field should have maximum size of 5 MB.',
        ];
    }
}
