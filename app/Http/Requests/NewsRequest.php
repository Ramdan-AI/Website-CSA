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
                'max:255'
            ],

            'slug' => [
                'required',
                'max:255'
            ],

            'content' => [
                'required'
            ],

            'status' => [
                'required'
            ],

            'published_at' => [
                'nullable'
            ]

        ];
    }
}
