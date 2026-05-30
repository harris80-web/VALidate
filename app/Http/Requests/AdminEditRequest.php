<?php

namespace App\Http\Requests;

use App\Enums\AdminRole;
use App\Enums\AdminStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use PhpParser\Builder\TraitUse;

class AdminEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'email' => ['required', 'email', 'max:250', Rule::unique('admins', 'email')->ignore(Auth::id()),],
            'name'  => 'required|string|max:250',
            'password' => ['nullable', 'string', 'min:8', 'max:100'],
            'status' => ['required', 'in:' . implode(',', AdminStatus::asArray())],
            'role' => ['required', 'in:' . implode(',', AdminRole::asArray())],
        ];
    }
}
