<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|min:5|max:255',
            'description' => 'nullable|string|max:2000',
            'destination' => 'nullable|string|min:2|max:255',
            'country_id' => 'sometimes|integer|min:1',
            'customer_id' => 'nullable|integer|exists:customers,id',
            'company_id' => 'nullable|integer|exists:companies,id',
            'cities' => 'sometimes|array',
            'cities.*.id' => 'integer|min:1',
            'cities.*.name' => 'string|max:100',
            'duration_days' => 'required|integer|min:1|max:180',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'price_per_person' => 'required|numeric|min:0.01|max:999999.99',
            'currency_id' => 'nullable|integer|exists:currencies,id',
            'capacity' => 'nullable|integer|min:1|max:1000',
            'image_url' => 'nullable|url|max:500',
            'inclusions' => 'nullable|array',
            'inclusions.*' => 'string|max:500',
            'exclusions' => 'nullable|array',
            'exclusions.*' => 'string|max:500',
            'itinerary' => 'nullable|array',
            'itinerary.*.day' => 'integer|min:1|max:180',
            'itinerary.*.title' => 'string|max:255',
            'itinerary.*.description' => 'string|max:2000',
            'status' => 'sometimes|in:active,inactive,archived',
            'department_id' => 'required|integer|exists:departments,id',
            'meta' => 'sometimes|array',
            'meta.offer_type' => 'sometimes|string|max:50',
            'meta.travelers' => 'sometimes|array',
            'meta.travelers.adults' => 'sometimes|integer|min:0',
            'meta.travelers.children' => 'sometimes|integer|min:0',
            'meta.travelers.infants' => 'sometimes|integer|min:0',
            'meta.notes' => 'sometimes|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Offer title is required',
            'duration_days.required' => 'Duration is required',
            'price_per_person.required' => 'Price per person is required',
            'department_id.required' => 'Department is required',
            'image_url.url' => 'Image URL must be a valid URL',
        ];
    }
}
