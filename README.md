# Pencatatan Transaksi - Sistem Pemantauan & Manajemen Penjualan

Sistem **Pencatatan Transaksi** adalah aplikasi berbasis web yang dibangun dengan Laravel untuk mengelola data penjualan, toko, area sales, dan nominal transaksi secara terintegrasi. Dilengkapi dengan antarmuka modern yang responsif serta fitur ekspor data yang instan (Excel & PDF).

---

## 🚀 Fitur Utama

Sistem ini terdiri dari 4 modul utama yang saling berhubungan:
1. **Data Toko (`table_a`)**: Manajemen kode toko baru dan kode toko lama, lengkap dengan fitur CRUD, impor data Excel, serta ekspor ke Excel/PDF.
2. **Data Nominal (`table_b`)**: Manajemen nominal transaksi berdasarkan kode toko, lengkap dengan fitur CRUD, impor data Excel, serta ekspor ke Excel/PDF.
3. **Data Area Sales (`table_c`)**: Pemetaan kode toko dengan area penugasan sales, lengkap dengan fitur CRUD, impor data Excel, serta ekspor ke Excel/PDF.
4. **Data Sales (`table_d`)**: Manajemen data nama sales berdasarkan kode sales, lengkap dengan fitur CRUD, impor data Excel, serta ekspor ke Excel/PDF.
5. **Dashboard**: Panel ringkasan data pencatatan transaksi yang bersih dan minimalis.

---

## 🛠️ Prasyarat (Requirements)

Sebelum memulai instalasi, pastikan lingkungan pengembangan lokal Anda telah memenuhi persyaratan berikut:
* **PHP** >= 8.1
* **Composer** (untuk dependensi Laravel)
* **MySQL / MariaDB** (melalui Laragon, XAMPP, atau instalasi lokal lainnya)
* **Node.js & NPM** (untuk kompilasi aset frontend)

---

## 📥 Panduan Instalasi & Setup

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi pada lingkungan lokal Anda:

### 1. Persiapan Project
Salin folder proyek atau clone repositori ini ke dalam direktori server lokal Anda (misal: `C:\laragon\www\usertest-kerja`).

### 2. Instal Dependensi PHP
Jalankan perintah berikut di terminal/command prompt pada direktori utama proyek:
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat dan sesuaikan konfigurasi database MySQL Anda:
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=
```

### 4. Setup Dependensi Frontend & Aset
Instal paket Node dan lakukan kompilasi aset menggunakan Vite jika perlu:
```bash
npm install
npm run build
```

---

## ⚠️ PERINGATAN PENTING: Pengaturan Database

> Proyek ini **tidak menggunakan Laravel Migrations** untuk membuat struktur tabel utama (`table_a`, `table_b`, `table_c`, dan `table_d`). Sebagai gantinya, Anda harus mengimpor skema database yang telah disediakan langsung ke database MySQL Anda.

### Cara Import Database:
1. Masuk ke program manajemen MySQL Anda (seperti **phpMyAdmin**, **HeidiSQL**, atau **DBeaver**).
2. Pilih database `test_kerja` yang telah dibuat.
3. Impor file database `.sql` yang diberikan (misalnya `test_kerja.sql` atau file dump yang disediakan oleh penguji) ke dalam database tersebut.

---

## 🏁 Menjalankan Aplikasi

Setelah semua langkah di atas selesai, jalankan server pengembangan lokal Laravel:
```bash
php artisan serve
```
Aplikasi sekarang dapat diakses melalui browser Anda di alamat: [http://localhost:8000](http://localhost:8000)
