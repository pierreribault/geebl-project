<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventPreparePaymentRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'tickets' => 'required|array',
            'tickets.*.amount' => 'required',
        ];
    }

    public function getTotalAmountUnits()
    {
        return collect($this->get('tickets'))
            ->sum(function ($ticket) {
                return $ticket['amount'];
            }) * 1000;
    }
}