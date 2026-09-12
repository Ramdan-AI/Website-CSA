<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'description' => [
                'nullable',
                'string'
            ],
        ];

        // File is required on create, optional on update
        if ($this->isMethod('POST')) {
            $rules['file'] = [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx',
                'max:10240'
            ];
        } else {
            $rules['file'] = [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx',
                'max:10240'
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'file.required' => 'File dokumen wajib diunggah.',
            'file.mimes' => 'File harus berupa PDF, DOC, DOCX, XLS, atau XLSX.',
            'file.max' => 'Ukuran file maksimal 10 MB.',
        ];
    }
}