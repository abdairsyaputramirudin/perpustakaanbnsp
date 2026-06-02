# Perpustakaan

Perpustakaan adalah aplikasi web berbasis Laravel untuk mengelola katalog buku, data anggota, eksemplar buku, peminjaman, dan pengembalian koleksi perpustakaan.

Project ini dibuat untuk Ujian Sertifikasi BNSP Okupasi Pemrogram dengan case utama aplikasi perpustakaan. Fokus utamanya adalah menyediakan katalog koleksi untuk anggota dan pencatatan peminjaman oleh petugas.

## Tujuan Aplikasi

- Menyediakan katalog koleksi buku yang bisa dilihat anggota tanpa login.
- Membantu petugas mengelola data buku, anggota, dan eksemplar buku.
- Mencatat peminjaman buku berdasarkan copy buku yang tersedia.
- Menghitung tanggal harus kembali otomatis 7 hari dari tanggal pinjam.
- Mencatat pengembalian buku dan mengembalikan status copy menjadi tersedia.
- Menampilkan status keterlambatan untuk peminjaman aktif dan peminjaman yang dikembalikan terlambat.

## Aktor Pengguna

- Anggota: melihat katalog buku, mencari buku, dan melihat detail buku tanpa login.
- Petugas: login untuk mengelola buku, anggota, peminjaman, pengembalian, dan dashboard.

## Fitur Utama

- Katalog publik untuk anggota.
- Detail katalog publik.
- Login petugas.
- Dashboard petugas.
- CRUD buku.
- Upload cover buku.
- Tampilan metadata buku dengan fokus pada kode, judul, penulis, penerbit, tahun, stok, cover, deskripsi, dan copy code.
- CRUD anggota.
- Book copies atau eksemplar buku.
- Peminjaman berdasarkan copy buku.
- Pengembalian buku.
- Due date otomatis 7 hari dari tanggal pinjam.
- Badge status peminjaman: `Dipinjam`, `Terlambat`, `Dikembalikan`, dan `Terlambat Dikembalikan`.
- Seeder data demo.
- Unit dan feature testing.

## Teknologi

- PHP
- Laravel
- MySQL
- Blade Template
- Bootstrap
- Eloquent ORM
- Carbon
- PHPUnit
- Laravel Storage public disk

## Struktur Folder Penting

```text
app/Http/Controllers    Controller untuk request web
app/Models              Model Eloquent dan relasi database
app/Services            Service layer untuk logika peminjaman
database/migrations     Struktur tabel database
database/seeders        Data awal untuk demo
resources/views         Tampilan Blade
routes/web.php          Daftar route aplikasi
tests/Feature           Feature test aplikasi
tests/Unit              Unit test helper/model
docs                    Dokumentasi, ERD, use case, testing
```

## Struktur Database Singkat

- `users`: menyimpan akun petugas untuk login.
- `members`: menyimpan data anggota perpustakaan.
- `books`: menyimpan data buku dan path cover.
- `book_copies`: menyimpan eksemplar fisik tiap buku.
- `loans`: menyimpan transaksi peminjaman utama.
- `loan_items`: menyimpan buku/copy yang dipinjam dalam satu transaksi.

Relasi intinya:

- Satu anggota bisa punya banyak peminjaman.
- Satu buku bisa punya banyak copy.
- Satu peminjaman bisa punya banyak item.
- Satu item peminjaman mencatat satu buku dan satu copy buku.

Dokumentasi ERD ada di `docs/ERD.md`.

## Instalasi

Clone atau salin project ke folder web server, lalu masuk ke folder project:

```bash
cd perpustakaan
```

Install dependency PHP:

```bash
composer install
```

Install dependency frontend jika diperlukan:

```bash
npm install
```

Copy file environment:

```bash
cp .env.example .env
```

Buat application key:

```bash
php artisan key:generate
```

## Konfigurasi Database

Project ini menggunakan MySQL. Contoh konfigurasi `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perpustakaan
DB_USERNAME=root
DB_PASSWORD=
```

Pastikan MySQL atau Laragon sudah aktif sebelum menjalankan migration.

## Migration dan Seeder

Jalankan migration dan seeder:

```bash
php artisan migrate:fresh --seed
```

Seeder akan membuat data awal buku, anggota, petugas, dan copy buku untuk kebutuhan demo.

## Storage Link untuk Cover Buku

Cover buku disimpan di disk `public`, sehingga perlu storage link:

```bash
php artisan storage:link
```

Jika muncul pesan bahwa link sudah ada, itu normal selama gambar tetap bisa diakses dari `public/storage`.

## Menjalankan Aplikasi

Jika memakai Laragon virtual host:

```text
http://perpustakaan.test
```

Atau jalankan dengan Artisan:

```bash
php artisan serve
```

Lalu buka:

```text
http://127.0.0.1:8000
```

## Akun Demo Petugas

```text
Email: petugas@perpustakaan.test
Password: password
```

Anggota tidak perlu login. Anggota cukup membuka katalog publik untuk melihat koleksi buku.

## Metadata Buku yang Ditampilkan

Metadata utama yang ditampilkan pada katalog dan detail buku adalah:

- Kode buku.
- Judul.
- Penulis.
- Penerbit.
- Tahun.
- Stok atau ketersediaan.
- Cover buku.
- Deskripsi.
- Copy code untuk eksemplar buku.

## Alur Peminjaman

1. Petugas login.
2. Petugas membuka menu peminjaman.
3. Petugas memilih anggota.
4. Petugas memilih copy buku yang statusnya `available`.
5. Petugas mengisi tanggal pinjam.
6. Sistem membuat due date otomatis 7 hari dari tanggal pinjam.
7. Sistem mencatat data ke tabel `loans` dan `loan_items`.
8. Copy buku berubah menjadi `borrowed`.
9. Stok buku disinkronkan dari jumlah copy yang masih `available`.

## Alur Pengembalian

1. Petugas membuka daftar peminjaman.
2. Petugas memilih transaksi yang masih berstatus `borrowed`.
3. Petugas memproses pengembalian.
4. Sistem mengisi tanggal kembali.
5. Status peminjaman berubah menjadi `returned`.
6. Copy buku berubah menjadi `available`.
7. Stok buku disinkronkan kembali.

Jika `return_date` melewati `due_date`, sistem tetap menampilkan status utama `Dikembalikan` dan menambahkan badge `Terlambat Dikembalikan`.

## Testing

Jalankan test:

```bash
php artisan test
```

Hasil testing terakhir:

```text
18 tests
60 assertions
0 failed
```

Laporan testing lengkap ada di `docs/TESTING_REPORT.md`.

## Dokumentasi Tambahan

- `docs/ERD.md`: struktur database dan relasi.
- `docs/USE_CASE.md`: use case diagram anggota dan petugas.
- `docs/TESTING_REPORT.md`: laporan unit dan feature testing.
- `docs/DOCUMENTATION.md`: dokumentasi
