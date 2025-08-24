<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Sistem Data Pegawai & Olah Artikel

Aplikasi berbasis Laravel untuk mengelola data pegawai dan mengolah artikel teks, meliputi:

- CRUD Pegawai

- Master Data (Jabatan, Unit Kerja, Golongan, dll)

- Upload foto pegawai

- Fitur filter dan pencarian

- Penggantian Kata (Replace)

- Pengurutan Kata (Sort)
## Tech Stack
- Backend: laravel 12
- Database: MySQL
- Frontend: Bootstrap 4
- Server: PHP8.2+, composer
## Installation

1. Clone Repository [project](https://github.com/anymore07/data-pegawai-pns.git).

```bash
git clone https://github.com/anymore07/data-pegawai-pns.git
cd data-pegawai-pns
```
2. Install dependencies
```bash
composer install
```
3. Copy file .env.example menjadi .env
```bash
cp .env.example .env
```
4. Generate key
```bash
php artisan key:generate
```
5. Setting koneksi database di .env
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=datapns
DB_USERNAME=root
DB_PASSWORD=
```
6. Jalankan migrasi
```bash
php artisan migrate --seed
```
7. jalankan aplikasi
```bash
php artisan serve
```
## Akses Login
Gunakan akun berikut untuk masuk ke aplikasi:

- Email: *superadmin@example.com*

- Password: *super123*

## Cara Penggunaan Sistem Data PNS
Login ke aplikasi menggunakan akun di atas.

Dashboard akan menampilkan ringkasan data pegawai.

Navigasi menu di sidebar:

- Master Data → Kelola Golongan, Jabatan, Unit Kerja.

- Pegawai → Tambah/Edit/Hapus Data Pegawai.

Form Input/Edit:

- Isi semua field sesuai kebutuhan.

- Untuk foto pegawai, bisa diupload. Saat edit, biarkan kosong jika tidak ingin mengganti.

Logout setelah selesai menggunakan aplikasi.
## Cara Penggunaan Alat Olah Artikel
Masuk ke halaman Alat Olah Artikel.

Paste artikel teks ke textarea.

Pilih salah satu aksi:

- Hitung Kata: isi field keyword, klik tombol Hitung → hasil jumlah kemunculan muncul.

- Ganti Kata: isi field dari dan ke, klik tombol Ganti → artikel diperbarui.

- Urutkan Kata: klik tombol Urutkan → daftar kata unik A–Z muncul.

## Contributing

*Chanesya Mey Kusuma*
## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
