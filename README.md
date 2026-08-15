# Sistem Manajemen Aset Sekolah — Backend

Backend API untuk aplikasi pengelolaan data aset/barang sekolah (laptop, proyektor, kursi, dan barang inventaris lainnya). Dibangun dengan Laravel sebagai REST API, dengan autentikasi dan pembatasan akses berbasis role.

## Fitur

- Autentikasi berbasis token (Laravel Sanctum)
- Dua role pengguna: **admin** (akses penuh) dan **staff** (hanya bisa melihat & menambah data)
- CRUD kategori aset
- CRUD lokasi/ruangan
- CRUD data aset, dengan fitur pencarian dan filter berdasarkan kategori/lokasi
- Kode aset ter-generate otomatis (format `AST-0001`, `AST-0002`, dst) supaya tidak ada input manual yang rawan typo atau duplikat
- Validasi mencegah kategori/lokasi dihapus selama masih dipakai oleh data aset

## Tech Stack

- **Framework**: Laravel 12 (API only)
- **Autentikasi**: Laravel Sanctum
- **Database**: MySQL

## Alur Autentikasi & Akses

flowchart TD
    A[Login - POST /api/login] --> B[Sanctum verifikasi kredensial<br/>lalu menerbitkan token]
    B --> C{Role?}
    C -->|admin| D[CRUD penuh:<br/>kategori, lokasi, aset]
    C -->|staff| E[Hanya bisa melihat<br/>dan menambah aset]

Setiap endpoint yang butuh login mewajibkan header `Authorization: Bearer <token>`. Pembatasan akses per role ditangani lewat middleware (`role:admin` dan `role:admin,staff`) di sisi backend — bukan sekadar disembunyikan di tampilan, jadi tetap terjaga meski ada percobaan akses langsung ke API tanpa lewat antarmuka.

## Struktur Database

users        : id, name, email, password, role (admin/staff)
categories   : id, nama_kategori
locations    : id, nama_ruangan, lokasi_gedung
assets       : id, kode_aset, nama_aset, category_id, location_id,
               kondisi, jumlah, tanggal_perolehan, keterangan, created_by

`categories` dan `locations` masing-masing memiliki relasi `hasMany` ke `assets`. Aset selalu tertaut ke kategori, lokasi, dan pengguna yang menginputnya (`created_by`).

## Endpoint API

**Autentikasi**

POST   /api/login
POST   /api/logout        (perlu token)
GET    /api/me             (perlu token)

**Kategori** (pola yang sama berlaku untuk `/api/locations`)

GET    /api/categories               admin, staff
GET    /api/categories/{id}          admin, staff
POST   /api/categories               admin
PUT    /api/categories/{id}          admin
DELETE /api/categories/{id}          admin

**Aset**

GET    /api/assets?search=&category_id=&location_id=   admin, staff
GET    /api/assets/{id}                                  admin, staff
POST   /api/assets                                        admin, staff
PUT    /api/assets/{id}                                   admin
DELETE /api/assets/{id}                                   admin

## Menjalankan di Lokal

```bash
git clone <url-repo-ini>
cd manajemen-aset-backend
composer install
cp .env.example .env
php artisan key:generate
```

Sesuaikan konfigurasi `DB_*` di `.env` dengan MySQL lokal, lalu:
```bash
php artisan migrate
php artisan serve
```

## Penggunaan AI dalam Pengerjaan Project

Saya menggunakan **Claude (Anthropic)** sebagai asisten selama membangun backend ini. Cara pakainya:

- **Diskusi struktur sebelum coding** — sebelum menulis kode, saya bahas dulu rancangan database (ERD) dan alur autentikasi/otorisasi dengan AI, supaya paham alasan di balik setiap keputusan struktur, bukan langsung minta jadi kode.
- **Diberi penjelasan per langkah, bukan cuma kode jadi** — setiap bagian (migration, model, middleware, controller) saya minta dijelaskan kenapa ditulis seperti itu, misalnya kenapa validasi dipisah ke Form Request, atau kenapa pengecekan role pakai middleware terpisah.
- **Bantuan debugging** — saat menemui error yang belum saya pahami (terutama soal konfigurasi CORS, routing API Laravel 11+, dan koneksi database saat deployment), saya tempel pesan error-nya ke AI dan diarahkan cara mendiagnosisnya, bukan langsung diberi solusi tanpa penjelasan.
- **Saya yang menjalankan dan memverifikasi semuanya sendiri** — setiap perintah tetap saya jalankan sendiri di terminal, saya baca hasil/errornya, dan saya yang memutuskan langkah berikutnya. AI berperan sebagai tempat bertanya dan diskusi, bukan yang mengerjakan project secara otomatis.

## Penulis

Muhammad Farid Malik
