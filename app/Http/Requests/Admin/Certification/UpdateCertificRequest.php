<?php

namespace App\Http\Requests\Admin\Certification;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCertificRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->user()->can('certification edit')) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes',
            'academy' => 'sometimes',
            'photo' => 'sometimes|image',
            'is_published' => 'sometimes',
        ];
    }
}
