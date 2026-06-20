<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Supplier - CV Wijaya Las</title>
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
            </ul>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-grow-1 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0"><i class="bi bi-eye me-2 text-info"></i>Detail Data Supplier</h4>
            <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <!-- Card Detail (Read Data Supplier) -->
        <div class="card shadow-sm" style="max-width: 700px;">
            <div class="card-header bg-info bg-opacity-10 border-0 py-3">
                <h5 class="mb-0 text-info fw-bold">
                    <i class="bi bi-truck me-2"></i>{{ $supplier->nama_supplier }}
                </h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th class="text-muted" style="width:160px">Nama Supplier</th>
                        <td>: {{ $supplier->nama_supplier }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Telepon</th>
                        <td>: {{ $supplier->telepon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Email</th>
                        <td>: {{ $supplier->email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Kontak Person</th>
                        <td>: {{ $supplier->kontak_person ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Alamat</th>
                        <td>: {{ $supplier->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Dibuat Pada</th>
                        <td>: {{ $supplier->created_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <th class="text-muted">Diupdate Pada</th>
                        <td>: {{ $supplier->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="card-footer bg-white border-0 d-flex gap-2">
                <!-- Tombol Update Data Supplier -->
                <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i>Edit Supplier
                </a>
                <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus supplier ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i>Hapus
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
