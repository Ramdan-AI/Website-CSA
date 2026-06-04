<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'title' => [
                'required',
                'max:255'
            ],

            'amount' => [
                'required',
                'numeric'
            ],

            'description' => [
                'nullable'
            ],

            'transaction_date' => [
                'required',
                'date'
            ]

        ];
    }
}
