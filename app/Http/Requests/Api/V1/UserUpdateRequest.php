<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->route('user');
        return $this->user()->can('update', $user);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user')->id;
        
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => [
                'sometimes',
                'email',
                'max:255',
                Rule::unique('users', 'email')->where(function ($query) use ($userId) {
                    return $query->where('tenant_id', auth()->user()->tenant_id)
                                 ->where('id', '!=', $userId);
                }),
            ],
            'password' => ['sometimes', 'string', 'min:8', 'confirmed'],
            'role_id' => ['sometimes', 'integer', 'exists:roles,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'status' => ['sometimes', 'string', Rule::in(['active', 'inactive'])],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.string' => 'User name must be a valid string',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'role_id.exists' => 'Selected role does not exist',
            'department_id.exists' => 'Selected department does not exist',
            'status.in' => 'Status must be either active or inactive',
        ];
    }
}
