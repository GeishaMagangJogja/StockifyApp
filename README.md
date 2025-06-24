# 📦 Stockify - Aplikasi Stok Gudang

> **Aplikasi untuk mengelola barang di gudang dengan mudah!** 🚀

Stockify adalah aplikasi web untuk mengelola stok barang di gudang. Cocok untuk toko, warehouse, atau bisnis yang perlu mencatat barang masuk dan keluar.

## ✨ Kenapa Pilih Stockify?

-   🔐 **Aman** - Ada sistem login untuk melindungi data
-   📱 **Mudah Digunakan** - Tampilan yang simpel dan mudah dipahami
-   💾 **Data Tersimpan** - Semua data barang tersimpan dengan aman
-   📊 **Laporan Lengkap** - Bisa lihat stok barang kapan saja
-   🔍 **Pencarian Cepat** - Mudah cari barang yang diinginkan

---

## 🛠️ Yang Digunakan

| Teknologi        | Fungsi                  |
| ---------------- | ----------------------- |
| **Laravel**      | Sistem utama aplikasi   |
| **MySQL**        | Tempat menyimpan data   |
| **Tailwind CSS** | Membuat tampilan cantik |

---

## 🚀 Cara Install

### Yang Perlu Dipersiapkan

Pastikan komputer sudah ada:

-   **PHP** versi 8.1 atau lebih baru ✅
-   **Composer** (untuk install Laravel) ✅
-   **MySQL** (tempat menyimpan data) ✅
-   **Git** (untuk download kode) ✅

### Langkah Install

**1. Download aplikasi**

```bash
git clone [https://github.com/GeishaMagangJogja/StockifyApp.git]
cd StockifyApp
```

**2. Install komponen Laravel**

```bash
composer install
```

**3. Setup konfigurasi**

```bash
cp .env.example .env
php artisan key:generate
```

**4. Setting database**
Buka file `.env` dan isi:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=stockify
DB_USERNAME=root
DB_PASSWORD=
```

**5. Buat tabel database**

```bash
php artisan migrate:fresh --seed
```

**6. Jalankan aplikasi**

```bash
php artisan serve
```

Buka browser ke: http://127.0.0.1:8000 🎉

---

## 📁 Struktur Folder

```
stockify-backend/
├── app/
│   ├── Http/Controllers/     # Pengendali halaman
│   ├── Models/              # Model data
│   └── Services/           # Logika bisnis
├── database/
│   ├── migrations/         # Struktur tabel
│   └── seeders/           # Data awal
├── routes/
│   └── api.php            # Alamat halaman
└── resources/
    └── css/              # File tampilan
```

---

## 🔗 Alamat Halaman (API)

### Login & Daftar

| Cara | Alamat               | Fungsi            |
| ---- | -------------------- | ----------------- |
| POST | `/api/auth/register` | Daftar akun baru  |
| POST | `/api/auth/login`    | Masuk ke aplikasi |

### Kelola Barang

| Cara   | Alamat               | Fungsi              |
| ------ | -------------------- | ------------------- |
| GET    | `/api/products`      | Lihat semua barang  |
| POST   | `/api/products`      | Tambah barang baru  |
| GET    | `/api/products/{id}` | Lihat detail barang |
| PUT    | `/api/products/{id}` | Edit barang         |
| DELETE | `/api/products/{id}` | Hapus barang        |

### Kelola Kategori

| Cara   | Alamat                 | Fungsi          |
| ------ | ---------------------- | --------------- |
| GET    | `/api/categories`      | Lihat kategori  |
| POST   | `/api/categories`      | Tambah kategori |
| PUT    | `/api/categories/{id}` | Edit kategori   |
| DELETE | `/api/categories/{id}` | Hapus kategori  |

### Profile Pengguna

| Cara | Alamat         | Fungsi        |
| ---- | -------------- | ------------- |
| GET  | `/api/profile` | Lihat profile |

---

## 🔐 Sistem Keamanan

Stockify menggunakan sistem token untuk keamanan.

### Cara menggunakan:

1. **Login** dulu untuk dapat token
2. **Masukkan token** di setiap request

### Contoh:

```bash
# Login dulu
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Akses data barang (pakai token)
curl -X GET http://localhost:8000/api/products \
  -H "Authorization: Bearer token-yang-didapat"
```

---

## 🧪 Testing

### Jalankan test:

```bash
# Test semua
php artisan test

# Test file tertentu
php artisan test tests/Feature/ProductControllerTest.php
```

---

## 🚢 Pasang di Server

### Persiapan production:

```bash
# Optimasi untuk server
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
```

### Setting server:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://website-kamu.com

# Database server
DB_HOST=alamat-database-server
DB_DATABASE=stockify_production
```

---

## 🤝 Kontribusi

Mau bantu develop? Silakan!

### Caranya:

1. **Fork** repository ini
2. **Buat branch** baru: `git checkout -b fitur-baru`
3. **Commit** perubahan: `git commit -m "tambah fitur baru"`
4. **Push**: `git push origin fitur-baru`
5. **Buat Pull Request**

---

## 📝 Tips Development

### Command berguna:

```bash
# Buat model baru dengan controller
php artisan make:model NamaBarang -mcr

# Hapus semua cache
php artisan optimize:clear

# Debug mode
php artisan tinker
```

---


