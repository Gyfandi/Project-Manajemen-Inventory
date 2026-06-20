<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Hanya Admin yang bisa menambahkan owner baru
        return auth()->check() && auth()->user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:owner,username', 'unique:users,username'],
            'email' => ['required', 'email', 'max:100', 'unique:owner,email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_telp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jabatan' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:aktif,nonaktif'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama owner wajib diisi.',
            'nama.max' => 'Nama owner maksimal 100 karakter.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.max' => 'Username maksimal 50 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'email.max' => 'Email maksimal 100 karakter.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }
}
