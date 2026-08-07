<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => [
                'required',
                'exists:news_categories,id'
            ],
            'title' => [
                'required',
                'string',
                'max:255'
            ],
            'content' => [
                'required',
                'string'
            ],
            'status' => [
                'required',
                'in:draft,published'
            ],
            'published_at' => [
                'nullable',
                'date'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status berita harus draft atau published.',
        ];
    }
}