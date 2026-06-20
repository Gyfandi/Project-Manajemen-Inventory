<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $owner = $this->route('owner');
        
        if (!auth()->check()) {
            return false;
        }

        // Admin bisa mengubah data siapa saja. Owner hanya bisa mengubah datanya sendiri.
        return auth()->user()->role === 'admin' || auth()->user()->id === $owner->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $owner = $this->route('owner');
        $ownerId = $owner->id;
        $userId = $owner->user_id;

        return [
            'nama' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'max:50', 'unique:owner,username,' . $ownerId, 'unique:users,username,' . $userId],
            'email' => ['required', 'email', 'max:100', 'unique:owner,email,' . $ownerId, 'unique:users,email,' . $userId],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
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
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
            'no_telp.max' => 'Nomor telepon maksimal 20 karakter.',
            'status.required' => 'Status wajib dipilih.',
            'status.in' => 'Status tidak valid.',
        ];
    }
}
