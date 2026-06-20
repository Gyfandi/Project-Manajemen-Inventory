<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Owner - CV Wijaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">Sistem Inventory CV Wijaya</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white-50 small">{{ Auth::user()->name }} ({{ ucfirst(Auth::user()->role) }})</span>
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
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link text-secondary">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    @else
                        <a href="{{ route('owner.dashboard') }}" class="nav-link text-secondary">
                            <i class="bi bi-speedometer2 me-2"></i>Dashboard
                        </a>
                    @endif
                </li>
                @if(Auth::user()->role === 'admin')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-people me-2"></i>Manajemen User
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-truck me-2"></i>Manajemen Supplier
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.secretaries.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-person-badge me-2"></i>Manajemen Sekretaris
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('owner.index') }}" class="nav-link active fw-semibold text-primary bg-primary bg-opacity-10 rounded">
                        <i class="bi bi-person-workspace me-2"></i>Manajemen Owner
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-person-workspace me-2 text-primary"></i>Manajemen Owner</h4>
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('owner.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Tambah Owner
            </a>
            @endif
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

        <!-- Form Pencarian & Filter -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('owner.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-muted small mb-1">Cari Owner</label>
                        <input type="text" name="search" class="form-control"
                                placeholder="Cari nama, username, email, atau jabatan..."
                                value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small mb-1">Filter Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="aktif" {{ ($status ?? '') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ ($status ?? '') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="bi bi-search me-1"></i>Filter & Cari
                        </button>
                    </div>
                    @if($search || $status)
                    <div class="col-md-2">
                        <a href="{{ route('owner.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i>Reset
                        </a>
                    </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Tabel Data Owner -->
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <span class="fw-semibold text-muted">
                    <i class="bi bi-list-ul me-1"></i>
                    Daftar Owner Perusahaan
                </span>
                <span class="badge bg-primary rounded-pill">{{ $owners->total() }} data</span>
            </div>
            <div class="card-body p-0">
                @if($owners->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                        Tidak ada data owner ditemukan.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th style="width:50px">No</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jabatan</th>
                                    <th>Email</th>
                                    <th>No Telepon</th>
                                    <th class="text-center">Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th style="width:150px" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($owners as $index => $owner)
                                <tr>
                                    <td class="text-muted">{{ $owners->firstItem() + $index }}</td>
                                    <td class="fw-semibold">{{ $owner->nama }}</td>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                            {{ $owner->jabatan ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $owner->email }}</td>
                                    <td>{{ $owner->no_telp }}</td>
                                    <td class="text-center">
                                        @if($owner->status === 'aktif')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $owner->created_at->format('d M Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('owner.show', $owner->id) }}"
                                           class="btn btn-info btn-sm text-white" title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if(Auth::user()->role === 'admin' || Auth::user()->id === $owner->user_id)
                                        <a href="{{ route('owner.edit', $owner->id) }}"
                                           class="btn btn-warning btn-sm text-white" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endif
                                        @if(Auth::user()->role === 'admin')
                                        <form action="{{ route('owner.destroy', $owner->id) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('Yakin ingin menghapus data owner ini? Akun user login terkait juga akan ikut terhapus.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
            @if($owners->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $owners->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
