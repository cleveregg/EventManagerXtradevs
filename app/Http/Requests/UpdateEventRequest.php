<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:255'],
            'date' => ['required', 'date', 'after:now'],
            'description' => ['nullable', 'string', 'max:5000'],
            'attendee_limit' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,webp', 'max:3072'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Az esemény neve kötelező.',
            'name.min' => 'Az esemény neve legalább :min karakter legyen.',
            'name.max' => 'Az esemény neve legfeljebb :max karakter lehet.',
            'date.required' => 'A dátum megadása kötelező.',
            'date.date' => 'Érvényes dátumot adjon meg.',
            'date.after' => 'A dátumnak a jövőben kell lennie.',
            'description.max' => 'A leírás legfeljebb :max karakter lehet.',
            'attendee_limit.required' => 'A férőhelyek száma kötelező.',
            'attendee_limit.integer' => 'A férőhelyek számának egész számnak kell lennie.',
            'attendee_limit.min' => 'A férőhelyek száma legalább :min legyen.',
            'image.image' => 'A fájlnak képnek kell lennie.',
            'image.mimes' => 'A kép formátuma jpg, png vagy webp lehet.',
            'image.max' => 'A kép mérete legfeljebb 3 MB lehet.',
        ];
    }
}
