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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'nis' => ['nullable', 'string', 'min:5', 'max:20', 'regex:/^[0-9]+$/'],
            'kelompok_asal' => ['nullable', 'string', 'in:IPA,IPS'],
            'foto' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'name.min' => 'Nama minimal 3 karakter.',
            'name.max' => 'Nama maksimal 255 karakter.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan akun lain.',

            'nis.min' => 'NIS minimal 5 digit.',
            'nis.max' => 'NIS maksimal 20 digit.',
            'nis.regex' => 'NIS hanya boleh berisi angka.',

            'kelompok_asal.in' => 'Kelompok asal harus IPA atau IPS.',

            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, PNG, atau GIF.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ];
    }
}
