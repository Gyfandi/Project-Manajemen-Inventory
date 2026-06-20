<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CV Wijaya Las</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <!-- Navbar -->
    <nav class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold text-gray-800">Sistem Inventory CV Wijaya Las</h1>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600">{{ Auth::user()->name }} (Admin)</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-red-500 hover:text-red-700">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Sidebar + Content -->
    <div class="flex">

        <!-- Sidebar -->
        <aside class="w-56 min-h-screen bg-white shadow p-4 mt-2">
            <ul class="space-y-2">
                <li class="font-semibold text-gray-700 px-2 py-1 bg-gray-100 rounded">Dashboard</li>
                <li><a href="{{ route('admin.users.index') }}" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen User</a></li>
                <li><a href="{{ route('admin.suppliers.index') }}" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen Supplier</a></li>
                <li><a href="{{ route('admin.secretaries.index') }}" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen Sekretaris</a></li>
                <li><a href="{{ route('owner.index') }}" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen Owner</a></li>
                <li><a href="#" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen Material</a></li>
                <li><a href="#" class="block px-2 py-1 text-gray-600 hover:bg-gray-100 rounded">Manajemen Kategori</a></li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-xl font-bold text-gray-700 mb-6">Dashboard Admin</h2>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-white rounded shadow p-4">
                    <p class="text-sm text-gray-500">Total User</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white rounded shadow p-4">
                    <p class="text-sm text-gray-500">Total Supplier</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white rounded shadow p-4">
                    <p class="text-sm text-gray-500">Total Material</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
                <div class="bg-white rounded shadow p-4">
                    <p class="text-sm text-gray-500">Total Kategori</p>
                    <p class="text-2xl font-bold text-gray-800">0</p>
                </div>
            </div>
        </main>

    </div>

</body>
</html>
