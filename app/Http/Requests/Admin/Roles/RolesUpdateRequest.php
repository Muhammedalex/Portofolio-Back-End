<?php

namespace App\Http\Requests\Admin\Roles;

use Illuminate\Foundation\Http\FormRequest;

class RolesUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->user()->can('country edit')){return true;}
    return false;
}
protected function failedAuthorization(){
    throw new \Illuminate\Auth\Access\AuthorizationException(__('auth.admin Unauthorised'));
}

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permissions' => ['required'],
            'permissions.*' => ['exists:permissions,name'],
            'role' => ['required', 'unique:roles,name,'. $this->role_id, 'max:60'],
        ];
    }
}
