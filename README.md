# Sistem Informasi Manajemen Inventory — CV Wijaya Las Kediri

Aplikasi manajemen inventory berbasis web untuk mencatat dan mengelola data material, supplier, transaksi material masuk/keluar, serta laporan transaksi, dengan tiga peran pengguna (Admin, Sekretaris, Owner). Dibangun menggunakan **Laravel 13**.

---

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Hak Akses & Role Pengguna](#hak-akses--role-pengguna)
- [Tech Stack](#tech-stack)
- [Struktur Database](#struktur-database)
- [Alur Bisnis](#alur-bisnis)
- [Instalasi & Setup](#instalasi--setup)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Struktur Folder Penting](#struktur-folder-penting)
- [Routing Ringkas](#routing-ringkas)
- [Catatan & Keterbatasan](#catatan--keterbatasan)
- [Roadmap / To-Do](#roadmap--to-do)
- [Lisensi](#lisensi)

---

## Fitur Utama

- **Autentikasi & Manajemen Akun** — login, register, reset password (berbasis Laravel Breeze), redirect otomatis ke dashboard sesuai role setelah login.
- **Manajemen User (Admin)** — CRUD akun pengguna beserta role (`admin`, `sekretaris`, `owner`).
- **Manajemen Sekretaris & Owner** — pendaftaran akun sekretaris/owner yang otomatis tersinkron dengan tabel `users`, lengkap dengan audit trail (`created_by`, `updated_by`).
- **Manajemen Material** — CRUD data material (nama, harga satuan, stok, kategori).
- **Manajemen Kategori** — CRUD kategori material, dengan proteksi agar kategori yang masih dipakai material tidak bisa dihapus.
- **Manajemen Supplier** — CRUD data supplier (nama, telepon, email, alamat, kontak person).
- **Entry Transaksi Material Masuk** — pencatatan barang masuk dari supplier, stok otomatis bertambah, kode transaksi otomatis (`MM-YYYYMMDD-XXXXX`).
- **Entry Transaksi Material Keluar** — pencatatan barang keluar, validasi stok mencukupi sebelum transaksi diproses, stok otomatis berkurang, kode transaksi otomatis (`MK-YYYYMMDD-XXXXX`).
- **Dashboard per Role** — ringkasan data (total user, material, transaksi bulan berjalan, stok menipis, aktivitas terbaru) untuk Admin; ringkasan transaksi untuk Sekretaris; ringkasan transaksi dengan filter tanggal untuk Owner.
- **Laporan Transaksi (Owner)** — filter laporan transaksi masuk/keluar berdasarkan rentang tanggal, tampilan cetak (print-friendly), dan export laporan ke **CSV**.

---

## Hak Akses & Role Pengguna

| Role | Hak Akses |
|---|---|
| **Admin** | Akses penuh: manajemen user, sekretaris, owner, material, kategori, supplier, entry transaksi masuk/keluar, dashboard ringkasan sistem. |
| **Sekretaris** | Input transaksi material masuk & keluar, melihat daftar transaksi, dashboard ringkas. |
| **Owner** | Melihat dashboard & laporan transaksi (dengan filter tanggal), cetak & export laporan, mengelola profil owner lain (khusus admin yang bisa menambah/menghapus owner). |

> ⚠️ Lihat bagian [Catatan & Keterbatasan](#catatan--keterbatasan) terkait proteksi akses dashboard antar-role.

---

## Tech Stack

| Komponen | Teknologi |
|---|---|
| Backend Framework | Laravel ^13.8 (PHP ^8.3) |
| Autentikasi | Laravel Breeze |
| Database (default) | SQLite (mudah diganti ke MySQL) |
| Export Excel | maatwebsite/excel ^3.1 (PhpSpreadsheet) |
| Frontend | Blade Templates, Tailwind CSS, Alpine.js |
| Build Tool | Vite |

---

## Struktur Database

```
users
 ├── id, name, username, email, password, role (admin|sekretaris|owner)

owner            (profil tambahan akun owner)
 ├── user_id (FK -> users), nama, username, email, password,
     no_telp, alamat, jabatan, status, created_by, updated_by

sekretaris       (profil tambahan akun sekretaris)
 ├── user_id (FK -> users), nama, username, email, password,
     no_telp, alamat, status, created_by, updated_by

kategoris
 ├── id, nama_kategori, admin_id (FK -> users)

materials
 ├── id, nama_material, harga_satuan, stok_material,
     kategori_id (FK -> kategoris), admin_id (FK -> users)

suppliers
 ├── id, nama_supplier, telepon, email, alamat, kontak_person

material_masuk   (transaksi barang masuk)
 ├── id, kode_transaksi, material_id (FK), supplier_id (FK),
     sekretaris_id (FK -> users), jumlah, harga_total

material_keluar  (transaksi barang keluar)
 ├── id, kode_transaksi, material_id (FK),
     sekretaris_id (FK -> users), jumlah, harga_total
```

**Relasi singkat:**
- `Material` → `belongsTo Kategori`, `belongsTo User (admin)`, `hasMany MaterialMasuk`, `hasMany MaterialKeluar`
- `MaterialMasuk` → `belongsTo Material`, `belongsTo Supplier`, `belongsTo User (sekretaris)`
- `MaterialKeluar` → `belongsTo Material`, `belongsTo User (sekretaris)`
- `Owner` / `Secretary` → `belongsTo User` (akun login), plus relasi `creator`/`updater`

---

## Alur Bisnis

### Transaksi Material Masuk
1. Sekretaris/Admin memilih material, supplier, dan jumlah barang masuk.
2. Sistem menghitung `harga_total = harga_satuan material × jumlah`.
3. Sistem membuat kode transaksi unik: `MM-YYYYMMDD-XXXXX`.
4. Data transaksi disimpan ke tabel `material_masuk`.
5. Stok material (`stok_material`) bertambah otomatis sesuai jumlah.

### Transaksi Material Keluar
1. Sekretaris/Admin memilih material dan jumlah barang keluar.
2. Sistem **memvalidasi stok mencukupi** — jika jumlah melebihi stok tersedia, transaksi ditolak dan menampilkan pesan error.
3. Jika valid, sistem menghitung `harga_total`, membuat kode transaksi `MK-YYYYMMDD-XXXXX`.
4. Data transaksi disimpan ke tabel `material_keluar`.
5. Stok material berkurang otomatis sesuai jumlah.

### Laporan (Owner)
1. Owner memilih rentang tanggal (`dari` – `sampai`), default bulan berjalan.
2. Sistem menampilkan daftar transaksi masuk & keluar beserta total nilai masing-masing.
3. Owner dapat mencetak laporan (tampilan print-friendly) atau mengunduh laporan dalam format **CSV**.

---

## Instalasi & Setup

### Prasyarat
- PHP `^8.3`
- Composer
- Node.js & NPM
- Ekstensi PHP: `sqlite3` (jika menggunakan SQLite) atau driver database lain sesuai konfigurasi

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <url-repo-anda>.git
cd <nama-folder-repo>

# 2. Install dependency PHP
composer install

# 3. Install dependency frontend
npm install

# 4. Salin file environment
cp .env.example .env

# 5. Generate application key
php artisan key:generate

# 6. Siapkan database (default: SQLite)
touch database/database.sqlite
# Pastikan di .env: DB_CONNECTION=sqlite

# 7. Jalankan migrasi
php artisan migrate

# 8. (Opsional) Jalankan seeder jika tersedia
php artisan db:seed
```

> **Menggunakan MySQL/MariaDB sebagai alternatif:**
> Edit `.env` dan sesuaikan:
> ```
> DB_CONNECTION=mysql
> DB_HOST=127.0.0.1
> DB_PORT=3306
> DB_DATABASE=nama_database
> DB_USERNAME=root
> DB_PASSWORD=
> ```
> Lalu jalankan `php artisan migrate` setelah database dibuat.

### Membuat Akun Awal

Karena belum ada seeder/route registrasi khusus role admin, buat akun admin pertama secara manual lewat Tinker:

```bash
php artisan tinker
```

```php
\App\Models\User::create([
    'name' => 'Admin Utama',
    'username' => 'admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password123'),
    'role' => 'admin',
]);
```

Akun `sekretaris` dan `owner` selanjutnya bisa dibuat lewat menu **Manajemen Sekretaris** / **Manajemen Owner** di dashboard Admin.

---

## Menjalankan Aplikasi

```bash
# Compile asset frontend (mode development, hot reload)
npm run dev

# Di terminal terpisah, jalankan server Laravel
php artisan serve
```

Aplikasi dapat diakses di `http://127.0.0.1:8000`.

Untuk build asset production:

```bash
npm run build
```

---

## Struktur Folder Penting

```
app/
├── Http/Controllers/
│   ├── Admin/             # Controller modul admin (User, Supplier, Secretary, Material, Kategori)
│   ├── Owner/              # Controller laporan owner (LaporanController)
│   ├── Sekretaris/         # Controller transaksi sekretaris (TransaksiController)
│   ├── Auth/               # Controller autentikasi (Laravel Breeze)
│   └── OwnerController.php # CRUD profil owner
├── Http/Middleware/
│   ├── AdminMiddleware.php # Membatasi akses khusus role admin
│   └── RoleMiddleware.php  # Membatasi akses berdasarkan daftar role (role:admin,owner)
├── Models/                 # Eloquent models (User, Owner, Secretary, Material, Kategori, Supplier, MaterialMasuk, MaterialKeluar)
├── Exports/                 # Class export Excel (LaporanExport, LaporanMasukSheet, LaporanKeluarSheet)
database/
├── migrations/              # Skema seluruh tabel
resources/views/
├── admin/                   # View modul admin
├── owner/                   # View modul owner
├── sekretaris/              # View modul sekretaris
└── layouts/                 # Layout utama (navbar, sidebar)
routes/
└── web.php                  # Seluruh routing aplikasi
```

---

## Routing Ringkas

| Area | Contoh Route | Middleware |
|---|---|---|
| Dashboard Admin | `GET /admin/dashboard` | `auth` |
| Dashboard Sekretaris | `GET /sekretaris/dashboard` | `auth` |
| Dashboard Owner | `GET /owner/dashboard` | `auth` |
| Laporan Owner | `GET /owner/laporan`, `/owner/laporan/cetak`, `/owner/laporan/export` | `auth` |
| Manajemen Owner | `Route::resource('owner', ...)` | `auth`, `role:admin,owner` |
| Transaksi Sekretaris | `/sekretaris/transaksi/masuk`, `/sekretaris/transaksi/keluar` (+ `/create` untuk form) | `auth` |
| Manajemen User/Supplier/Sekretaris/Material/Kategori | `Route::resource('admin/...', ...)` | `auth`, `admin` |
| Profile | `/profile` (edit, update, destroy) | `auth` |

---

## Catatan & Keterbatasan

Beberapa hal yang perlu diperhatikan/diperbaiki sebelum digunakan secara produksi:

- ⚠️ **Proteksi role pada route dashboard belum lengkap.** Route `/admin/dashboard`, `/sekretaris/dashboard`, dan `/owner/dashboard` hanya dibungkus middleware `auth`, sehingga pengguna dengan role apa pun yang sudah login bisa mengakses ketiga URL dashboard tersebut (walau modul CRUD-nya sendiri tetap terkunci middleware `admin`/`role`). Disarankan menambahkan middleware role pada masing-masing route dashboard.
- 🧹 **Terdapat kode yang tidak terpakai (dead code):**
  - `app/Http/Controllers/Sekretaris/EntryTransaksiController.php` beserta view `sekretaris/entry/*` — sudah digantikan oleh `TransaksiController` namun belum dihapus.
  - `app/Http/Controllers/Owner/LaporanController.php` beserta sistem export Excel (`app/Exports/*`) — sudah lengkap dan fungsional, tetapi route aktif `/owner/laporan*` di `routes/web.php` justru menggunakan closure terpisah dengan export **CSV manual**, bukan controller/export Excel ini.
  - `app/Http/Controllers/SupplierController.php` (namespace root) — tidak digunakan di routing dan akan error jika dipanggil (kurang `use App\Models\Supplier;`).
- 🔁 **Duplikasi data akun** — tabel `owner` dan `sekretaris` menyimpan ulang `nama`, `username`, `email`, `password` yang sebenarnya sudah ada di tabel `users`, dihubungkan via `user_id`. Sinkronisasi dilakukan manual di controller menggunakan `DB::transaction()`.
- 🔒 Belum ada `DB::transaction()` pada proses simpan transaksi + update stok material secara bersamaan, sehingga berpotensi terjadi race condition pada akses concurrent tinggi (risiko rendah untuk skala penggunaan kecil/menengah).
- 📦 Database default menggunakan SQLite untuk kemudahan development; gunakan MySQL/PostgreSQL untuk lingkungan produksi.

---

## Roadmap / To-Do

- [ ] Tambahkan middleware role pada route dashboard admin/sekretaris/owner.
- [ ] Hapus atau aktifkan kembali `EntryTransaksiController` & view terkait.
- [ ] Alihkan route `/owner/laporan/export` untuk menggunakan `LaporanExport` (Excel) yang sudah dibuat, atau hapus salah satu implementasi agar tidak duplikat.
- [ ] Hapus `app/Http/Controllers/SupplierController.php` (root) yang tidak terpakai dan rusak.
- [ ] Bungkus proses simpan transaksi + update stok dalam `DB::transaction()`.
- [ ] Tambahkan unit/feature test untuk alur transaksi masuk & keluar.

---

## Lisensi

Proyek ini dibuat untuk keperluan tugas akhir mata kuliiah rekayasa perangkat lunak. 
