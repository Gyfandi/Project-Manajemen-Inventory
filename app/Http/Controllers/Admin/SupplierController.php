<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Menampilkan daftar data supplier (Read Data Supplier)
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $suppliers = Supplier::when($search, function ($query, $search) {
            return $query->where('nama_supplier', 'like', "%{$search}%")
                         ->orWhere('telepon', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
        })->latest()->get();

        return view('admin.suppliers.index', compact('suppliers', 'search'));
    }

    /**
     * Menampilkan form tambah supplier
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Simpan supplier baru ke database
     */
    public function store(Request $request)
    {
        // Validasi input (menvalidasi)
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'alamat'        => 'nullable|string',
            'kontak_person' => 'nullable|string|max:255',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
        ]);

        Supplier::create($request->only([
            'nama_supplier', 'telepon', 'email', 'alamat', 'kontak_person'
        ]));

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Data supplier berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail supplier (Read Data Supplier)
     */
    public function show(Supplier $supplier)
    {
        return view('admin.suppliers.show', compact('supplier'));
    }

    /**
     * Menampilkan form Update Data Supplier
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.suppliers.edit', compact('supplier'));
    }

    /**
     * Update data supplier (update data suplier + menvalidasi)
     */
    public function update(Request $request, Supplier $supplier)
    {
        // Validasi input (menvalidasi)
        $request->validate([
            'nama_supplier' => 'required|string|max:255',
            'telepon'       => 'nullable|string|max:20',
            'email'         => 'nullable|email|max:255',
            'alamat'        => 'nullable|string',
            'kontak_person' => 'nullable|string|max:255',
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
        ]);

        $supplier->update($request->only([
            'nama_supplier', 'telepon', 'email', 'alamat', 'kontak_person'
        ]));

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Data supplier berhasil diupdate!');
    }

    /**
     * Hapus supplier
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
                         ->with('success', 'Data supplier berhasil dihapus!');
    }
}
