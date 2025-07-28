<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitir o uso da request sem políticas
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'min:3', 'max: 20'],
            'last_name' => ['required', 'string', 'min:3', 'max: 50'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->route('user'))],
            'administrator' => ['required', 'boolean', 'in: 0,1'],
            'cpf' => ['required', 'string', 'size:11', Rule::unique('users', 'cpf')->ignore($this->route('user'))],
            'position' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'cep' => ['required', 'string', 'size:8'],
            'address' => ['required', 'string', 'max:80'],
            'number' => ['required', 'string', 'max:10'],
            'complement' => ['string', 'max:20'],
            'district' => ['required', 'string', 'max:50'],
            'city' => ['required', 'string', 'max:50'],
            'state' => ['required', 'string', 'size:2'],
        ];
    }
}
