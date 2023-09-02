<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpaceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'space-image' => 'mimes:jpeg,png,jpg|max:1024',
            'space-name' => 'required|min:3|max:30',
            'selected-users' => 'required',
            'space-description' => 'max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'space-image.mimes' => 'Only jpeg,png,jpg are supported',
            'space-image.max' => 'The image is too big',
            'space-name.required' => 'Name is required',
            'space-name.min' => 'Name should be at least 3 characters long',
            'space-name.max' => 'Name should be maximum 30 characters long',
            'space-description.max' => 'Description should be maximum 255 characters long',
            'selected-users.required' => 'You should assign at least one user that will manage this space',
        ];
    }
}
