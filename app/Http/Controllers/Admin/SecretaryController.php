<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Secretary;
use App\Models\User;
use App\Http\Requests\Admin\StoreSecretaryRequest;
use App\Http\Requests\Admin\UpdateSecretaryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SecretaryController extends Controller
{
    /**
     * Tampilkan daftar sekretaris dengan pencarian dan filter
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $secretaries = Secretary::when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('sekretaris.index', compact('secretaries', 'search', 'status'));
    }

    /**
     * Tampilkan form tambah sekretaris
     */
    public function create()
    {
        return view('sekretaris.create');
    }

    /**
     * Simpan data sekretaris baru (sinkron dengan tabel users)
     */
    public function store(StoreSecretaryRequest $request)
    {
        DB::transaction(function () use ($request) {
            // 1. Buat user untuk login terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'sekretaris',
            ]);

            // 2. Buat data sekretaris dan hubungkan dengan user_id
            Secretary::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('admin.secretaries.index')
                         ->with('success', 'Sekretaris berhasil didaftarkan!');
    }

    /**
     * Tampilkan detail sekretaris
     */
    public function show(Secretary $secretary)
    {
        // Load relasi pembuat & pengubah untuk audit trail
        $secretary->load(['user', 'creator', 'updater']);

        return view('sekretaris.show', compact('secretary'));
    }

    /**
     * Tampilkan form edit sekretaris
     */
    public function edit(Secretary $secretary)
    {
        return view('sekretaris.edit', compact('secretary'));
    }

    /**
     * Perbarui data sekretaris (sinkron dengan tabel users)
     */
    public function update(UpdateSecretaryRequest $request, Secretary $secretary)
    {
        DB::transaction(function () use ($request, $secretary) {
            // 1. Update data akun User
            $user = $secretary->user;
            if ($user) {
                $user->update([
                    'name' => $request->nama,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);

                if ($request->password) {
                    $user->update([
                        'password' => Hash::make($request->password)
                    ]);
                }
            }

            // 2. Update data profil Sekretaris
            $secretary->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'status' => $request->status,
            ]);

            if ($request->password) {
                $secretary->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        });

        return redirect()->route('admin.secretaries.index')
                         ->with('success', 'Data sekretaris berhasil diperbarui!');
    }

    /**
     * Hapus data sekretaris beserta akun usernya
     */
    public function destroy(Secretary $secretary)
    {
        DB::transaction(function () use ($secretary) {
            // Hapus user terkait (akan otomatis men-trigger cascade delete pada sekretaris jika diset di foreign key, 
            // namun kita hapus keduanya secara eksplisit untuk keamanan)
            $user = $secretary->user;
            $secretary->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('admin.secretaries.index')
                         ->with('success', 'Data sekretaris berhasil dihapus!');
    }
}
