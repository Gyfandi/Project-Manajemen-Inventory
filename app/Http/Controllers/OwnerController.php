<?php

namespace App\Http\Controllers;

use App\Models\Owner;
use App\Models\User;
use App\Http\Requests\Admin\StoreOwnerRequest;
use App\Http\Requests\Admin\UpdateOwnerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    /**
     * Tampilkan daftar owner dengan pencarian dan filter
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');

        $owners = Owner::when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('nama', 'like', "%{$search}%")
                      ->orWhere('username', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('jabatan', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('owner.index', compact('owners', 'search', 'status'));
    }

    /**
     * Tampilkan form tambah owner (Khusus Admin)
     */
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Administrator yang dapat menambahkan owner baru.');
        }

        return view('owner.create');
    }

    /**
     * Simpan data owner baru (sinkron dengan tabel users)
     */
    public function store(StoreOwnerRequest $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Administrator yang dapat menambahkan owner baru.');
        }

        DB::transaction(function () use ($request) {
            // 1. Buat user untuk login terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
            ]);

            // 2. Buat data owner dan hubungkan dengan user_id
            Owner::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
            ]);
        });

        return redirect()->route('owner.index')
                         ->with('success', 'Owner baru berhasil didaftarkan!');
    }

    /**
     * Tampilkan detail owner
     */
    public function show(Owner $owner)
    {
        // Load relasi pembuat & pengubah untuk audit trail
        $owner->load(['user', 'creator', 'updater']);

        return view('owner.show', compact('owner'));
    }

    /**
     * Tampilkan form edit owner
     */
    public function edit(Owner $owner)
    {
        $user = Auth::user();

        // Otorisasi: Admin bisa mengedit siapa saja. Owner hanya bisa mengedit miliknya sendiri.
        if ($user->role !== 'admin' && $user->id !== $owner->user_id) {
            abort(403, 'Akses ditolak. Anda hanya diperbolehkan mengubah data profil Anda sendiri.');
        }

        return view('owner.edit', compact('owner'));
    }

    /**
     * Perbarui data owner (sinkron dengan tabel users)
     */
    public function update(UpdateOwnerRequest $request, Owner $owner)
    {
        $userLogin = Auth::user();

        // Otorisasi tambahan di level Controller
        if ($userLogin->role !== 'admin' && $userLogin->id !== $owner->user_id) {
            abort(403, 'Akses ditolak. Anda hanya diperbolehkan mengubah data profil Anda sendiri.');
        }

        DB::transaction(function () use ($request, $owner) {
            // 1. Update data akun User
            $user = $owner->user;
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

            // 2. Update data profil Owner
            $owner->update([
                'nama' => $request->nama,
                'username' => $request->username,
                'email' => $request->email,
                'no_telp' => $request->no_telp,
                'alamat' => $request->alamat,
                'jabatan' => $request->jabatan,
                'status' => $request->status,
            ]);

            if ($request->password) {
                $owner->update([
                    'password' => Hash::make($request->password)
                ]);
            }
        });

        // Redirect sesuai role
        if ($userLogin->role === 'owner') {
            return redirect()->route('owner.show', $owner->id)
                             ->with('success', 'Profil Anda berhasil diperbarui!');
        }

        return redirect()->route('owner.index')
                         ->with('success', 'Data owner berhasil diperbarui!');
    }

    /**
     * Hapus data owner beserta akun usernya (Khusus Admin)
     */
    public function destroy(Owner $owner)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Hanya Administrator yang dapat menghapus owner.');
        }

        // Cegah menghapus owner aktif jika hanya ada 1 owner tersisa di sistem
        $totalOwners = Owner::where('status', 'aktif')->count();
        if ($totalOwners <= 1 && $owner->status === 'aktif') {
            return redirect()->route('owner.index')
                             ->with('error', 'Gagal menghapus! Minimal harus terdapat satu owner berstatus aktif di dalam sistem.');
        }

        DB::transaction(function () use ($owner) {
            $user = $owner->user;
            $owner->delete();
            if ($user) {
                $user->delete();
            }
        });

        return redirect()->route('owner.index')
                         ->with('success', 'Data owner berhasil dihapus!');
    }
}
