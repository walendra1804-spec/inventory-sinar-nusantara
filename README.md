# Aplikasi Inventory & Penjualan — PT Sinar Nusantara

Aplikasi untuk mengelola persediaan barang di beberapa gudang, transaksi penjualan, transfer antar gudang, dan laporan.
Dibuat untuk Tes Fullstack Programmer Kabayan Group.

## Teknologi
| Bagian | Yang dipakai |
|---|---|
| Framework | Laravel 12 (PHP 8.2) |
| Database | MySQL 8.0 |
| Tampilan | Blade + template AdminLTE 3 (Bootstrap 4, jQuery) |
| Pola | MVC Laravel: Route → Controller → Model → View |

## Cara Menjalankan
```bash
git clone <url-repo-ini>
cd inventory
composer install
cp .env.example .env
php artisan key:generate
```
Atur koneksi database di `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_kabayan
DB_USERNAME=root
DB_PASSWORD=
```
Buat database `inventory_kabayan`, lalu:
```bash
php artisan migrate --seed
php artisan serve
```
Buka http://127.0.0.1:8000

## Akun Login (dari seeder)
| Role | Email | Password | Hak akses |
|---|---|---|---|
| Administrator | admin@gmail.com | 12345678 | Semua fitur |
| Operator | operator@gmail.com | 12345678 | Hanya transaksi |

Tidak ada halaman registrasi karena aplikasi hanya dipakai pegawai internal.

## Fitur
1. **Login & role** — admin mengakses semua fitur, operator hanya transaksi (dijaga middleware `auth` dan `role:admin`, bukan hanya disembunyikan dari menu).
2. **Master Barang** — SKU (unik), nama, kategori, satuan, harga pokok, harga jual, status aktif.
3. **Master Gudang** — CRUD.
4. **Master Pelanggan** — nama, nomor HP (opsional), alamat.
5. **Barang Masuk** — banyak barang dalam satu transaksi.
6. **Penjualan** — banyak barang dalam satu transaksi, harga disalin saat transaksi dibuat.
7. **Transfer antar gudang**.
8. **Pembatalan transaksi** — riwayat tidak dihapus.
9. **Dashboard** — total barang, gudang, pelanggan, penjualan hari ini, barang dengan stok terendah.
10. **Laporan** — stok, barang masuk, penjualan; filter tanggal, gudang, barang; export CSV.

## Struktur Database
| Tabel | Isi |
|---|---|
| `users` | akun + kolom `role` (admin / operator) |
| `barangs` | master barang |
| `gudangs` | master gudang |
| `pelanggans` | master pelanggan |
| `transaksis` | header transaksi: no, jenis (masuk / penjualan / transfer), tanggal, gudang, gudang tujuan, pelanggan, user penginput, status (selesai / batal) |
| `transaksi_details` | barang-barang dalam satu transaksi: barang, jumlah, harga |
| `stok_mutasis` | **kartu stok**: setiap perubahan stok (+ masuk, − keluar) per barang per gudang |

Relasi: `transaksis` 1 → banyak `transaksi_details`; `transaksis` 1 → banyak `stok_mutasis`. Semua relasi memakai foreign key.

## Keputusan Desain (menjawab ketentuan Direktur)

**1. Stok selalu sesuai histori transaksi**
Stok **tidak disimpan** sebagai angka di tabel barang. Stok **dihitung** dari kartu stok:
`stok = SUM(jumlah) di stok_mutasis untuk barang & gudang tersebut`.
Karena stok dihitung langsung dari histori, angkanya tidak mungkin berbeda dengan histori.

**2. Seluruh transaksi dapat ditelusuri**
Setiap transaksi menyimpan siapa yang menginput (`user_id`) dan kapan. Setiap baris kartu stok menyimpan `transaksi_id`.
Halaman detail transaksi menampilkan barang, harga, dan seluruh pergerakan stok yang dihasilkan transaksi tersebut, termasuk siapa yang membatalkan.

**3. Tidak boleh terjadi stok negatif**
- Penjualan dan transfer dicek dulu: jumlah tidak boleh melebihi stok di gudang asal.
- Pembatalan juga dicek: membatalkan barang masuk yang barangnya sudah terjual akan ditolak.
- Proses simpan memakai **DB transaction** (`beginTransaction` / `commit` / `rollBack`), jadi kalau satu barang gagal, seluruh transaksi batal (tidak ada data setengah jadi).
- Baris barang **dikunci** (`lockForUpdate`) di awal proses, sehingga jika dua user menjual barang yang sama bersamaan, yang kedua menunggu yang pertama selesai lalu mengecek stok terbaru.

**4. Database mudah dikembangkan**
- Satu tabel `transaksis` dengan kolom `jenis` untuk semua jenis transaksi + satu tabel kartu stok generik. Jenis transaksi baru (misalnya retur atau stock opname) cukup menambah nilai `jenis` dan aturan mutasinya, tanpa tabel baru.
- Pola header–detail, foreign key di semua relasi.
- Kategori dan satuan berupa teks agar admin bebas menambah nilai baru tanpa mengubah struktur tabel (bisa dipisah menjadi tabel master jika dibutuhkan).

**5. Hanya untuk pegawai internal**
Semua halaman wajib login, tidak ada registrasi, akun dibuat lewat seeder.

**Lainnya**
- **Harga tidak berubah** walaupun harga master berubah: harga disalin ke `transaksi_details.harga` saat transaksi dibuat (harga jual untuk penjualan, harga pokok untuk barang masuk & transfer).
- **Pembatalan tidak menghapus data**: status diubah menjadi `batal` dan dibuat kartu stok kebalikannya (+ menjadi −, − menjadi +), seperti jurnal pembalik di akuntansi.
- **Master yang sudah dipakai transaksi tidak bisa dihapus** (dicek di controller dan dijaga foreign key). Barang yang tidak dijual lagi cukup dinonaktifkan; barang nonaktif tidak bisa dipilih di transaksi baru.
- Harga memakai `integer` karena Rupiah tidak memakai sen (tidak memakai float untuk uang).
- Laporan dan dashboard hanya menghitung transaksi berstatus `selesai`.

## Catatan / Batasan
- "Stok per tanggal" di laporan stok dihitung dari waktu pencatatan kartu stok (`created_at`), bukan tanggal yang diketik di form transaksi.
- Pesan validasi masih bawaan Laravel (bahasa Inggris).
- Nomor transaksi berupa urutan `TRX-00001`, `TRX-00002`, dst.
