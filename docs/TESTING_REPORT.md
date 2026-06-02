# Laporan Testing

Dokumen ini berisi ringkasan unit dan feature testing pada project `perpustakaan`.

## Ringkasan Hasil Testing

```text
Command: php artisan test
Result : 18 tests, 60 assertions, 0 failed
Status : PASS
```

## Daftar File Test

- `tests/Feature/AuthStaffFeatureTest.php`
- `tests/Feature/BookFeatureTest.php`
- `tests/Feature/ExampleTest.php`
- `tests/Feature/LoanFeatureTest.php`
- `tests/Feature/PublicCatalogFeatureTest.php`
- `tests/Unit/ExampleTest.php`
- `tests/Unit/LoanOverdueUnitTest.php`

## Tabel Test Case

|  No | Nama Test                                                           | Skenario                                             | Data Uji                                                      | Expected Result                                                  | Actual Result                                | Status |
| --: | ------------------------------------------------------------------- | ---------------------------------------------------- | ------------------------------------------------------------- | ---------------------------------------------------------------- | -------------------------------------------- | ------ |
|   1 | `test_dashboard_cannot_be_accessed_without_login`                   | User belum login membuka dashboard.                  | Request `GET /dashboard`.                                     | Redirect ke halaman login.                                       | Redirect ke login.                           | PASS   |
|   2 | `test_staff_can_login_and_access_dashboard`                         | Petugas login dengan kredensial valid.               | Email `petugas@perpustakaan.test`, password `password`.       | Login berhasil dan dashboard bisa diakses.                       | Redirect dashboard dan authenticated.        | PASS   |
|   3 | `test_staff_can_open_book_detail_page`                              | Petugas membuka detail buku.                         | Buku `BK777`.                                                 | Halaman detail tampil dan judul terlihat.                        | Status 200 dan judul terlihat.               | PASS   |
|   4 | `test_staff_can_upload_book_cover_when_creating_book`               | Petugas membuat buku dengan cover JPG.               | File `cover.jpg`.                                             | Buku tersimpan dan cover ada di storage public.                  | Cover path tersimpan dan file ada.           | PASS   |
|   5 | `test_staff_can_upload_png_and_webp_cover_when_creating_book`       | Petugas upload cover PNG dan WEBP.                   | File `cover.png` dan `cover.webp`.                            | Dua buku tersimpan dan cover ada.                                | Cover PNG dan WEBP tersimpan.                | PASS   |
|   6 | `test_root_redirects_to_public_catalog`                             | User membuka root aplikasi.                          | Request `GET /`.                                              | Redirect ke katalog publik.                                      | Redirect ke `katalog.index`.                 | PASS   |
|   7 | `test_public_catalog_can_be_accessed_without_login`                 | Guest membuka katalog publik.                        | Buku `BK101`.                                                 | Katalog tampil tanpa login.                                      | Status 200 dan buku terlihat.                | PASS   |
|   8 | `test_public_catalog_detail_can_be_accessed_without_login`          | Guest membuka detail katalog.                        | Buku `BK100`.                                                 | Detail buku tampil tanpa login.                                  | Status 200 dan judul terlihat.               | PASS   |
|   9 | `test_can_create_loan_and_due_date_is_seven_days_after_borrow_date` | Petugas membuat peminjaman.                          | Anggota, buku, copy available, tanggal `2026-05-30`.          | Loan tercatat, due date `2026-06-06`, copy borrowed, stok turun. | Database sesuai expected.                    | PASS   |
|  10 | `test_stock_is_restored_when_loan_marked_as_returned`               | Petugas memproses pengembalian.                      | Loan aktif dengan satu copy borrowed.                         | Loan returned, copy available, stok kembali.                     | Database sesuai expected.                    | PASS   |
|  11 | `test_loan_fails_when_no_copy_selected`                             | Peminjaman tanpa memilih copy.                       | `book_copy_ids` kosong.                                       | Validasi gagal dan loan tidak dibuat.                            | Error `book_copy_ids`, database loan kosong. | PASS   |
|  12 | `test_loan_fails_when_copy_is_not_available`                        | Peminjaman copy yang tidak tersedia.                 | Copy status `borrowed`.                                       | Validasi gagal dan loan tidak dibuat.                            | Error `book_copy_ids`, database loan kosong. | PASS   |
|  13 | `test_deleting_active_loan_restores_book_stock`                     | Petugas menghapus loan aktif.                        | Loan aktif dengan copy borrowed.                              | Loan terhapus, copy available, stok kembali.                     | Database sesuai expected.                    | PASS   |
|  14 | `test_book_with_active_loan_cannot_be_deleted`                      | Petugas menghapus buku yang sedang dipinjam.         | Buku punya loan aktif.                                        | Penghapusan ditolak.                                             | Session error dan buku tetap ada.            | PASS   |
|  15 | `test_member_with_active_loan_cannot_be_deleted`                    | Petugas menghapus anggota yang punya loan aktif.     | Anggota punya loan aktif.                                     | Penghapusan ditolak.                                             | Session error dan anggota tetap ada.         | PASS   |
|  16 | `test_is_overdue_returns_true_for_borrowed_loan_past_due_date`      | Helper overdue dicek untuk loan lewat due date.      | Status `borrowed`, due date `2026-06-05`, today `2026-06-10`. | `isOverdue()` bernilai true.                                     | True.                                        | PASS   |
|  17 | `test_is_overdue_returns_false_for_returned_loan`                   | Helper overdue dicek untuk loan yang sudah returned. | Status `returned`, due date sudah lewat.                      | `isOverdue()` bernilai false.                                    | False.                                       | PASS   |
|  18 | `test_that_true_is_true`                                            | Test template dasar.                                 | Boolean `true`.                                               | Assertion true berhasil.                                         | Assertion berhasil.                          | PASS   |

## Cakupan Fitur yang Diuji

- Login petugas.
- Akses dashboard dengan auth.
- Katalog publik tanpa login.
- Detail katalog publik.
- Upload cover buku JPG, PNG, dan WEBP.
- Create loan.
- Due date otomatis 7 hari dari tanggal pinjam.
- Copy berubah menjadi `borrowed` saat dipinjam.
- Copy kembali menjadi `available` saat dikembalikan.
- Validasi copy kosong.
- Validasi copy tidak tersedia.
- Delete loan aktif mengembalikan stok/copy.
- Proteksi delete buku dengan peminjaman aktif.
- Proteksi delete anggota dengan peminjaman aktif.
- Helper overdue pada model `Loan` untuk peminjaman aktif.

## Kesimpulan Testing

Testing sudah mencakup requirement inti aplikasi perpustakaan: katalog, login petugas, peminjaman, due date 7 hari, status copy, stok, pengembalian, upload cover, dan overdue aktif. Status `Terlambat Dikembalikan` dicatat sebagai validasi manual karena belum ada automated test khusus.
