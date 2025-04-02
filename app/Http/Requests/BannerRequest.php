<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
        if (!$this->id) {
            return [
                'page' => 'required',
                'pageTitle' => 'nullable|max:255',
                'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:100',
            ];
        } else {
            return [
                'page' => 'nullable',
                'pageTitle' => 'nullable|max:255',
                'banner' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:100',
            ];
        }
    }

    public function attributes()
    {
        return [
            'page' => 'Page',
            'pageTitle' => 'Page title',
            'banner' => 'Banner',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => ':Attribute is required',
            'pageTitle.max' => 'Page title must not exceed 255 characters',
            'banner.image' => 'Banner must be an image',
            'banner.mimes' => 'Invalid file type. Allowed: jpeg, png, jpg, webp',
            'banner.max' => 'File size must be less than 500 KB',
        ];
    }
}
