<?php

namespace App\Http\Requests;

use App\Services\InquiryService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InquirySubmitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $site = $this->input('site');
        if ($site && ! $this->input('directory_url')) {
            $this->merge(['directory_url' => $site]);
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:40'],
            'directory_url' => ['nullable', 'string', 'max:500'],
            'need' => ['required', 'string', Rule::in(InquiryService::NEED_OPTIONS)],
            'message' => ['nullable', 'string', 'max:4000'],
            'source' => ['nullable', 'string', 'max:32', Rule::in(['llm-agent', 'web', 'api'])],
            'form_guard' => ['nullable', 'string', 'max:512'],
            'company_website' => ['nullable', 'string', 'max:200'],
            'fax_number' => ['nullable', 'string', 'max:200'],
        ];
    }
}
