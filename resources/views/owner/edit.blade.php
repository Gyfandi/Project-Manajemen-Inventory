<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Owner - CV Wijaya</title>
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
        <div class="mb-4">
            @if(Auth::user()->role === 'admin')
            <a href="{{ route('owner.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
            </a>
            @else
            <a href="{{ route('owner.show', $owner->id) }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i>Kembali ke Detail
            </a>
            @endif
            <h4 class="fw-bold"><i class="bi bi-pencil-square me-2 text-warning"></i>Ubah Data Owner</h4>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <span class="fw-semibold text-muted">
                    <i class="bi bi-person-gear me-1"></i>
                    Form Edit Owner: {{ $owner->nama }}
                </span>
            </div>
            <div class="card-body">
                <form action="{{ route('owner.update', $owner->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <!-- Nama Lengkap -->
                        <div class="col-md-6">
                            <label for="nama" class="form-label fw-semibold text-muted small">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama', $owner->nama) }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Username -->
                        <div class="col-md-6">
                            <label for="username" class="form-label fw-semibold text-muted small">Username Login <span class="text-danger">*</span></label>
                            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $owner->username) }}" required>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label fw-semibold text-muted small">Alamat Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $owner->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- No Telp -->
                        <div class="col-md-6">
                            <label for="no_telp" class="form-label fw-semibold text-muted small">Nomor Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="no_telp" id="no_telp" class="form-control @error('no_telp') is-invalid @enderror" value="{{ old('no_telp', $owner->no_telp) }}" required>
                            @error('no_telp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Jabatan (Hanya Admin yang bisa ubah) -->
                        <div class="col-md-6">
                            <label for="jabatan" class="form-label fw-semibold text-muted small">Jabatan Perusahaan</label>
                            <input type="text" name="jabatan" id="jabatan" class="form-control @error('jabatan') is-invalid @enderror" value="{{ old('jabatan', $owner->jabatan) }}" {{ Auth::user()->role !== 'admin' ? 'disabled' : '' }}>
                            @if(Auth::user()->role !== 'admin')
                                <input type="hidden" name="jabatan" value="{{ $owner->jabatan }}">
                            @endif
                            @error('jabatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status (Hanya Admin yang bisa ubah) -->
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold text-muted small">Status Akun <span class="text-danger">*</span></label>
                            <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" {{ Auth::user()->role !== 'admin' ? 'disabled' : '' }} required>
                                <option value="aktif" {{ old('status', $owner->status) === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $owner->status) === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @if(Auth::user()->role !== 'admin')
                                <input type="hidden" name="status" value="{{ $owner->status }}">
                            @endif
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password (Optional) -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-semibold text-muted small">Password Login baru (Optional)</label>
                            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="Biarkan kosong jika tidak diubah">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-semibold text-muted small">Konfirmasi Password baru</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Biarkan kosong jika tidak diubah">
                        </div>

                        <!-- Alamat -->
                        <div class="col-12">
                            <label for="alamat" class="form-label fw-semibold text-muted small">Alamat Tempat Tinggal</label>
                            <textarea name="alamat" id="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $owner->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-save me-1"></i>Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
