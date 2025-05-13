<?php

namespace App\Http\Requests;

use App\Models\NewsEvent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class NewsEventsRequest extends FormRequest
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
                'title' => ['required', 'max:255', function ($attribute, $value, $fail) {
                    $slug = Str::slug($value);
                    $check = NewsEvent::where('slug', $slug)->first();
                    if ($check) {
                        return $fail('Title already exists');
                    }
                }],
                'eventDate' => 'nullable|date|before:today',
                'file' => 'required|max:512',
            ];
        } else {
            return [
                'title' => 'required|max:255',
                'eventDate' => 'nullable|date|before:today',
                'file' => 'nullable|max:512',
            ];
        }
    }

    public function attributes()
    {
        return [
            'title' => 'Title',
            'eventDate' => 'Event date',
            'file' => 'File',
        ];
    }

    public function messages()
    {
        return [
            '*.required' => ':Attribute is required',
            'title.max' => 'Title must not exceed 255 characters',
            'eventDate.date' => 'Event date must be a valid date',
            'eventDate.before' => 'Event date must be before today',
            'file.max' => 'File size must be less than 200 KB',
        ];
    }
}
