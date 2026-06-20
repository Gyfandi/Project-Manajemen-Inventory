<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sekretaris - CV Wijaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">Sistem Inventory CV Wijaya</a>
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
                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link text-secondary">
                        <i class="bi bi-truck me-2"></i>Manajemen Supplier
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.secretaries.index') }}" class="nav-link active fw-semibold text-primary bg-primary bg-opacity-10 rounded">
                        <i class="bi bi-person-badge me-2"></i>Manajemen Sekretaris
                    </a>
                </li>
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <div class="mb-4">
            <a href="{{ route('admin.secretaries.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
            </a>
            <h4 class="fw-bold"><i class="bi bi-eye me-2 text-info"></i>Detail Data Sekretaris</h4>
        </div>

        <div class="row g-4">
            <!-- Profil Sekretaris -->
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <span class="fw-semibold text-muted"><i class="bi bi-person-lines-fill me-1"></i>Informasi Profil</span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 250px" class="ps-3 text-muted">ID Sekretaris</th>
                                    <td>{{ $secretary->id }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Nama Lengkap</th>
                                    <td class="fw-semibold text-primary">{{ $secretary->nama }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Username</th>
                                    <td><code>{{ $secretary->username }}</code></td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Email</th>
                                    <td>{{ $secretary->email }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">No. Telepon</th>
                                    <td>{{ $secretary->no_telp ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Alamat Rumah</th>
                                    <td>{{ $secretary->alamat ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th class="ps-3 text-muted">Status Akun</th>
                                    <td>
                                        @if($secretary->status === 'aktif')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">Aktif</span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Audit Trail -->
            <div class="col-md-4">
                <div class="card shadow-sm border-start border-info border-3">
                    <div class="card-header bg-white py-3">
                        <span class="fw-semibold text-muted"><i class="bi bi-clock-history me-1"></i>Audit Trail</span>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold d-block mb-1">Dibuat Oleh</label>
                            <span class="text-dark">{{ $secretary->creator->name ?? 'System' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold d-block mb-1">Tanggal Dibuat</label>
                            <span class="text-dark">{{ $secretary->created_at->format('d M Y H:i:s') }}</span>
                        </div>
                        <hr class="text-muted">
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-semibold d-block mb-1">Diubah Terakhir Oleh</label>
                            <span class="text-dark">{{ $secretary->updater->name ?? 'System' }}</span>
                        </div>
                        <div class="mb-0">
                            <label class="form-label text-muted small fw-semibold d-block mb-1">Tanggal Diubah</label>
                            <span class="text-dark">{{ $secretary->updated_at->format('d M Y H:i:s') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
