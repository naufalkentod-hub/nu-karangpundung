<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInfaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'idempotency_key' => ['required', 'uuid'],
            'donor_name' => ['nullable', 'required_unless:is_anonymous,1', 'string', 'max:120'],
            'is_anonymous' => ['sometimes', 'boolean'],
            'amount' => ['required', 'integer', 'min:1', 'max:1000000000'],
            'message' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', Rule::in(['manual_transfer'])],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'amount.integer' => 'Nominal infaq harus berupa bilangan bulat.',
            'amount.min' => 'Nominal infaq harus lebih dari Rp0.',
            'amount.max' => 'Nominal maksimal per transaksi adalah Rp1.000.000.000.',
            'payment_method.in' => 'Metode pembayaran tersebut belum tersedia.',
            'consent.accepted' => 'Persetujuan tujuan penggunaan dana wajib diberikan.',
        ];
    }
}
