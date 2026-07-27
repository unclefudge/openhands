<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnquiryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'organisation' => ['nullable', 'string', 'max:180'],
            'phone' => ['nullable', 'string', 'max:80'],
            'service' => [
                'required',
                Rule::in([
                    'A custom web application',
                    'Help with an existing Laravel system',
                    'Zoho or workflow automation',
                    'Ongoing development support',
                    'Not sure yet',
                ]),
            ],
            'timeframe' => [
                'nullable',
                Rule::in([
                    'As soon as practical',
                    'Within 1–3 months',
                    'Within 3–6 months',
                    'Exploring for later',
                ]),
            ],
            'referral' => [
                'nullable',
                Rule::in([
                    'Personal referral',
                    'LinkedIn',
                    'Existing client or colleague',
                    'Web search',
                    'Other',
                ]),
            ],
            'message' => ['required', 'string', 'min:30', 'max:5000'],
            'genuine' => ['accepted'],
            'website' => ['nullable', 'string', 'max:200'],
            'form_started_at' => ['required', 'string'],
            'cf-turnstile-response' => [
                Rule::requiredIf(filled(config('services.turnstile.secret_key'))),
                'nullable',
                'string',
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'service.required' => 'Please select the kind of help you are looking for.',
            'message.min' => 'Please include a little more detail about your project or problem.',
            'genuine.accepted' => 'Please confirm this is a genuine project or development enquiry.',
        ];
    }
}
