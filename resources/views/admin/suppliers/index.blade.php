<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Supplier - CV Wijaya Las</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">Sistem Inventory CV Wijaya Las</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white-50 small">{{ Auth::user()->name }} (Admin)</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <aside class="bg-white shadow-sm" style="min-width:220px; min-height:calc(100vh - 56px);">
        <div class="p-3">
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link text-secondary">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-people me-2"></i>Manajemen User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link active fw-semibold text-primary bg-primary bg-opacity-10 rounded">
                        <i class="bi bi-truck me-2"></i>Manajemen Supplier
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.secretaries.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-person-badge me-2"></i>Manajemen Sekretaris
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('owner.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-person-workspace me-2"></i>Manajemen Owner
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-truck me-2 text-primary"></i>Manajemen Supplier</h4>
            <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Supplier
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Form Pencarian (Read Data Supplier) -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('admin.suppliers.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label class="form-label text-muted small mb-1">Cari Supplier</label>
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari nama, telepon, atau email supplier..."
                               value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-search me-1"></i>Cari
                        </button>
                    </div>
                    @if($search)
                    <div class="col-md-2">
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Data Supplier (Menampilkan Data Suplier) -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold text-muted">
                    <i class="bi bi-list-ul me-1"></i>
                    Daftar Supplier
                    @if($search)
                        <span class="badge bg-secondary ms-1">Filter: "{{ $search }}"</span>
                    @endif
                </span>
                <span class="badge bg-primary rounded-pill">{{ $suppliers->count() }} data</span>
            </div>
            <div class="card-body p-0">
                @if($suppliers->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                        @if($search)
                            Tidak ada supplier dengan kata kunci "{{ $search }}"
                        @else
                            Belum ada data supplier. <a href="{{ route('admin.suppliers.create') }}">Tambah sekarang</a>
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:50px">No</th>
                                    <th>Nama Supplier</th>
                                    <th>Telepon</th>
                                    <th>Email</th>
                                    <th>Kontak Person</th>
                                    <th>Alamat</th>
                                    <th style="width:150px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $index => $supplier)
                                <tr>
                                    <td class="text-muted">{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $supplier->nama_supplier }}</td>
                                    <td>{{ $supplier->telepon ?? '-' }}</td>
                                    <td>{{ $supplier->email ?? '-' }}</td>
                                    <td>{{ $supplier->kontak_person ?? '-' }}</td>
                                    <td>
                                        <span class="text-truncate d-inline-block" style="max-width:150px" title="{{ $supplier->alamat }}">
                                            {{ $supplier->alamat ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <!-- Read Data Supplier -->
                                        <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                           class="btn btn-info btn-sm" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <!-- Update Data Supplier -->
                                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                           class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <!-- Hapus -->
                                        <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
