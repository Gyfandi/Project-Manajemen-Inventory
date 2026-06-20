<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request){
    // 1. Tangkap kata kunci pencarian dari form (jika ada)
    $search = $request->input('search');

    // 2. Ambil data dari database (Filter jika ada pencarian, jika tidak ambil semua)
    $suppliers = Supplier::when($search, function ($query, $search) {
        return $query->where('nama_supplier', 'like', "%{$search}%")
                     ->orWhere('telepon', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
    })->get();

    // 3. Kirim variabel $suppliers dan $search ke file blade kamu
   return view('admin.supplier.index', compact('suppliers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
