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
                'string',
                'max:255'
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1'
            ],
            'description' => [
                'nullable',
                'string'
            ],
            'transaction_date' => [
                'required',
                'date'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'amount.min' => 'Jumlah pengeluaran harus lebih dari 0.',
        ];
    }
}