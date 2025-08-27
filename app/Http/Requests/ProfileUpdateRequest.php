<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9]+$/',
                Rule::unique(User::class)->ignore($this->user()->id)
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
                'ends_with:@student.telkomuniversity.ac.id'
            ],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s]+$/'],
            'gender' => ['required', 'in:male,female'],
            'faculty' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'batch' => ['required', 'string', 'size:4', 'regex:/^[0-9]{4}$/'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nim.regex' => 'NIM harus berupa angka.',
            'email.ends_with' => 'Email harus menggunakan domain @student.telkomuniversity.ac.id',
            'phone.regex' => 'Format nomor telepon tidak valid.',
            'batch.size' => 'Angkatan harus 4 digit tahun.',
            'batch.regex' => 'Angkatan harus berupa tahun (contoh: 2024).',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus JPEG, PNG, atau JPG.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
