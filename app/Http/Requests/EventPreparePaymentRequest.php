<?php

namespace App\Http\Requests;

use App\Models\TicketCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Validation\Rules\In;

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
            'order' => 'required|array',
            'order.*.quantity' => 'required|integer|min:1|max:10',
            'order.*.id' => ['required', 'uuid', (new Exists('ticket_categories', 'id'))->where('event_id', $this->route('event')->id)],
        ];
    }

    public function getTotalAmountCents()
    {
        return collect($this->get('order'))
            ->sum(function ($category) {
                return TicketCategory::where('id', $category['id'])->firstOrFail()->price * $category['quantity'];
            }) * 100;
    }

    public function getTotalAmountUnits()
    {
        return collect($this->get('order'))
            ->sum(function ($category) {
                return TicketCategory::where('id', $category['id'])->firstOrFail()->price * $category['quantity'];
            });
    }
}
