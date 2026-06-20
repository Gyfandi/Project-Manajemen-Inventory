<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Owner - CV Wijaya</title>
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
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('owner.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                </a>
                @else
                <a href="{{ route('owner.dashboard') }}" class="btn btn-outline-secondary btn-sm mb-3">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Dashboard
                </a>
                @endif
                <h4 class="fw-bold"><i class="bi bi-eye me-2 text-info"></i>Detail Data Owner</h4>
            </div>
            @if(Auth::user()->role === 'admin' || Auth::user()->id === $owner->user_id)
            <a href="{{ route('owner.edit', $owner->id) }}" class="btn btn-warning text-white">
                <i class="bi bi-pencil me-1"></i>Edit Data Owner
            </a>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">
            <!-- Profil Owner -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <span class="fw-semibold text-muted"><i class="bi bi-person me-1"></i>Informasi Profil</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 200px" class="ps-3 text-muted">ID Owner</th>
                                    <td>{{ $owner->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Nama Lengkap</th>
                                    <td class="fw-semibold text-dark">{{ $owner->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Username Login</th>
                                    <td><code>{{ $owner->username }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Alamat Email</th>
                                    <td><a href="mailto:{{ $owner->email }}" class="text-decoration-none">{{ $owner->email }}</a></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Nomor Telepon</th>
                                    <td>{{ $owner->no_telp }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Jabatan</th>
                                    <td>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                            {{ $owner->jabatan ?? '-' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Status Akun</th>
                                    <td>
                                        @if($owner->status === 'aktif')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Alamat Rumah</th>
                                    <td class="text-secondary">{{ $owner->alamat ?? '-' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Audit Trail & Keamanan -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <span class="fw-semibold text-muted"><i class="bi bi-clock-history me-1"></i>Audit Trail & Log</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 200px" class="ps-3 text-muted">Dibuat Pada</th>
                                    <td>{{ $owner->created_at->format('d F Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Dibuat Oleh</th>
                                    <td>{{ $owner->creator->name ?? 'System Seeder / Default' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Terakhir Diupdate</th>
                                    <td>{{ $owner->updated_at->format('d F Y H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Diupdate Oleh</th>
                                    <td>{{ $owner->updater->name ?? 'System Seeder / Default' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-white text-muted small py-3">
                        <i class="bi bi-info-circle me-1 text-primary"></i>
                        Data ini secara otomatis terekam oleh sistem untuk keperluan audit keamanan data CV Wijaya.
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
