# Dokumentasi Penggunaan Aplikasi Perpustakaan

## 1. Gambaran Umum Aplikasi

Aplikasi Perpustakaan adalah aplikasi web berbasis Laravel dan MySQL untuk membantu pengelolaan koleksi buku, anggota, peminjaman, dan pengembalian buku.

Aplikasi ini digunakan untuk:

- Menyediakan katalog buku bagi anggota atau publik.
- Membantu petugas mencatat peminjaman dan pengembalian.
- Mengelola data buku, anggota, dan eksemplar buku.

## 2. Aktor Pengguna

Ada dua aktor utama pada aplikasi ini:

1. Anggota/Publik
2. Petugas

Anggota atau publik tidak perlu login dan hanya dapat melihat katalog buku. Petugas harus login untuk mengelola data buku, anggota, peminjaman, pengembalian, dan dashboard.

## 3. Penggunaan sebagai Anggota/Publik

### 3.1 Melihat Katalog Buku

Langkah penggunaan:

1. Buka aplikasi.
2. Masuk ke menu Katalog Publik.
3. Sistem menampilkan daftar buku.
4. Anggota dapat melihat cover, judul, penulis, penerbit, dan status ketersediaan buku.

### 3.2 Mencari Buku

Langkah penggunaan:

1. Isi kolom pencarian.
2. Cari buku berdasarkan kode, judul, penulis, atau penerbit.
3. Klik tombol Cari.
4. Sistem menampilkan hasil pencarian sesuai kata kunci.

### 3.3 Melihat Detail Buku

Langkah penggunaan:

1. Klik tombol Detail pada salah satu buku.
2. Sistem menampilkan detail buku.
3. Informasi yang tampil meliputi kode buku, judul, penulis, penerbit, tahun, deskripsi, cover, dan status ketersediaan.

Catatan: Anggota/Publik tidak dapat menambah, mengedit, menghapus buku, mencatat peminjaman, atau memproses pengembalian.

## 4. Penggunaan sebagai Petugas

### 4.1 Login Petugas

Langkah penggunaan:

1. Buka halaman login.
2. Masukkan email dan password.
3. Klik Login.
4. Jika data benar, sistem masuk ke dashboard petugas.

Akun demo petugas:

- Email: `petugas@perpustakaan.test`
- Password: `password`

### 4.2 Melihat Dashboard

Dashboard menampilkan ringkasan data perpustakaan seperti jumlah buku, anggota, peminjaman aktif, peminjaman selesai, stok kosong, dan status peminjaman yang perlu diperhatikan.

### 4.3 Mengelola Buku

Langkah penggunaan:

1. Masuk ke menu Buku.
2. Petugas dapat melihat daftar buku.
3. Petugas dapat menambah buku baru.
4. Petugas dapat mengedit data buku.
5. Petugas dapat menghapus buku jika tidak memiliki peminjaman aktif atau histori yang dilindungi sistem.

### 4.4 Upload Cover Buku

Langkah penggunaan:

1. Masuk ke form tambah atau edit buku.
2. Pilih file gambar cover.
3. Simpan data buku.
4. Cover tampil di katalog publik dan halaman detail buku.

Catatan upload cover:

- Format gambar yang didukung: jpg, jpeg, png, webp.
- Ukuran maksimal: 2 MB.
- Upload cover membutuhkan storage link Laravel dengan command `php artisan storage:link`.

### 4.5 Melihat Detail Buku dan Eksemplar

Langkah penggunaan:

1. Klik Detail pada daftar buku.
2. Sistem menampilkan informasi buku.
3. Sistem menampilkan daftar copy code atau eksemplar buku.
4. Status copy utama adalah Tersedia dan Dipinjam.

Copy code digunakan agar peminjaman mencatat eksemplar buku yang spesifik, bukan hanya judul bukunya saja.

### 4.6 Mengelola Anggota

Langkah penggunaan:

1. Masuk ke menu Anggota.
2. Petugas dapat menambah anggota.
3. Petugas dapat mengedit data anggota.
4. Petugas dapat menghapus anggota jika tidak memiliki peminjaman aktif atau histori yang dilindungi sistem.

### 4.7 Mencatat Peminjaman Buku

Langkah penggunaan:

1. Masuk ke menu Peminjaman.
2. Klik Tambah Peminjaman.
3. Pilih anggota.
4. Pilih copy buku yang tersedia.
5. Isi tanggal pinjam.
6. Sistem otomatis menghitung tanggal harus kembali 7 hari dari tanggal pinjam.
7. Simpan peminjaman.
8. Sistem mengubah status copy menjadi Dipinjam.

Sistem memakai due date otomatis +7 hari dari tanggal pinjam.

### 4.8 Memproses Pengembalian Buku

Langkah penggunaan:

1. Masuk ke menu Peminjaman.
2. Pilih data peminjaman yang masih Dipinjam.
3. Klik tombol Kembali.
4. Sistem mengubah status peminjaman menjadi Dikembalikan.
5. Sistem mengubah status copy kembali menjadi Tersedia.

### 4.9 Status Keterlambatan

Status peminjaman yang ditampilkan:

- Dipinjam: buku masih dipinjam dan belum melewati due date.
- Dipinjam + Terlambat: buku masih dipinjam dan sudah melewati due date.
- Dikembalikan: buku sudah dikembalikan tepat waktu.
- Dikembalikan + Terlambat Dikembalikan: buku sudah dikembalikan, tetapi return date melewati due date.

### 4.10 Logout

Langkah penggunaan:

1. Klik tombol Logout.
2. Sistem mengakhiri session petugas.
3. Petugas keluar dari area operasional.

## 5. Ringkasan Hak Akses

| Fitur                  | Anggota/Publik   | Petugas |
| ---------------------- | ---------------- | ------- |
| Melihat katalog        | Ya               | Ya      |
| Mencari buku           | Ya               | Ya      |
| Melihat detail buku    | Ya               | Ya      |
| Login                  | Tidak diperlukan | Ya      |
| Mengelola buku         | Tidak            | Ya      |
| Upload cover           | Tidak            | Ya      |
| Mengelola anggota      | Tidak            | Ya      |
| Mencatat peminjaman    | Tidak            | Ya      |
| Memproses pengembalian | Tidak            | Ya      |
| Melihat dashboard      | Tidak            | Ya      |

## 6. How To Run

Langkah menjalankan project:

1. Clone atau copy project.
2. Masuk ke folder project.
3. Jalankan `composer install`.
4. Copy `.env.example` menjadi `.env`.
5. Atur database MySQL.
6. Jalankan `php artisan key:generate`.
7. Jalankan `php artisan migrate:fresh --seed`.
8. Jalankan `php artisan storage:link`.
9. Jalankan `php artisan test`.
10. Jalankan `php artisan serve`.
11. Buka aplikasi di `http://127.0.0.1:8000`.

## 7. Akun Demo

Petugas:

- Email: `petugas@perpustakaan.test`
- Password: `password`
