<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Owner - CV Wijaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand fw-bold" href="#">Sistem Inventory CV Wijaya</a>
    <div class="ms-auto d-flex align-items-center gap-3">
        <span class="text-white-50 small">{{ Auth::user()->name }} (Owner)</span>
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
                    <a href="{{ route('owner.dashboard') }}" class="nav-link active fw-semibold text-primary bg-primary bg-opacity-10 rounded">
                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
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
            <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Owner</h4>
        </div>

        <div class="card shadow-sm p-4 mb-4 bg-white rounded">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-4">
                    <i class="bi bi-person-workspace display-6 text-primary"></i>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Selamat Datang Kembali, {{ Auth::user()->name }}!</h5>
                    <p class="text-muted mb-0">Anda masuk sebagai Owner. Melalui dashboard ini Anda dapat melihat daftar owner serta memperbarui profil pribadi Anda.</p>
                </div>
            </div>
        </div>

        <!-- Shortcut Panel -->
        <div class="row g-4">
            <!-- Profil Saya -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-person-badge me-2 text-info"></i>Profil Saya</h5>
                            <p class="text-muted small">Lihat detail profil Anda yang terdaftar pada sistem inventory CV Wijaya Las.</p>
                        </div>
                        <div class="mt-3">
                            @php
                                $ownerRecord = \App\Models\Owner::where('user_id', Auth::id())->first();
                            @endphp
                            @if($ownerRecord)
                                <a href="{{ route('owner.show', $ownerRecord->id) }}" class="btn btn-outline-info w-100">
                                    <i class="bi bi-eye me-1"></i>Lihat Profil Lengkap
                                </a>
                            @else
                                <span class="text-danger small"><i class="bi bi-exclamation-triangle me-1"></i>Data profil owner tidak ditemukan. Hubungi Administrator.</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hubungi Admin -->
            <div class="col-md-6">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-body d-flex flex-column justify-content-between">
                        <div>
                            <h5 class="fw-bold text-dark mb-2"><i class="bi bi-shield-lock me-2 text-warning"></i>Manajemen Owner</h5>
                            <p class="text-muted small">Melihat daftar owner perusahaan yang bertanggung jawab terhadap sistem CV Wijaya.</p>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('owner.index') }}" class="btn btn-outline-primary w-100">
                                <i class="bi bi-people me-1"></i>Lihat Semua Owner
                            </a>
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
