<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'starts_at' => ['required', 'date_format:Y-m-d H:i', 'after:now'],
            'customer_name' => ['required', 'string', 'max:100'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function attributes(): array
    {
        return [
            'starts_at' => 'tijdstip',
            'customer_name' => 'naam',
            'customer_email' => 'e-mailadres',
            'customer_phone' => 'telefoonnummer',
            'notes' => 'opmerking',
        ];
    }

    public function messages(): array
    {
        return [
            'starts_at.required' => 'Kies een tijdstip voor je afspraak.',
        ];
    }
}
