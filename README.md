# 📦 Stockify - Manajemen Stok Gudang Modern

**Stockify** adalah aplikasi web berbasis Laravel untuk mengelola stok barang masuk dan keluar di gudang, toko, atau bisnis retail. Dirancang agar mudah digunakan, aman, dan mendukung pelaporan stok serta transaksi secara real-time.

---

## 🚀 Fitur Utama

- **Manajemen Produk & Kategori**  
  Tambah, edit, hapus produk dan kategori dengan mudah.

- **Transaksi Barang Masuk & Keluar**  
  Catat setiap pergerakan stok, baik barang masuk maupun keluar.

- **Laporan & Statistik**  
  Lihat laporan stok, riwayat transaksi, dan statistik harian/mingguan.

- **Multi User Role**  
  Mendukung Admin, Manajer Gudang, dan Staff Gudang dengan hak akses berbeda.

- **Supplier Management**  
  Kelola data supplier barang.

- **Export/Import Excel**  
  Ekspor laporan atau template produk ke Excel, serta import data produk.

- **Keamanan & Audit**  
  Sistem login, otorisasi, dan pencatatan aktivitas pengguna.

---

## 🛠️ Teknologi yang Digunakan

| Teknologi        | Keterangan                |
|------------------|--------------------------|
| Laravel 10+      | Framework utama backend  |
| MySQL/MariaDB    | Database relasional      |
| Tailwind CSS     | Styling modern & responsif |
| Blade            | Templating Laravel       |
| FontAwesome      | Ikon UI                  |
| Maatwebsite/Excel| Export/Import Excel      |
| Carbon           | Manipulasi tanggal/waktu |

---

## 📁 Struktur Folder Penting

```
StockifyApp/
├── app/
│   ├── Http/Controllers/   # Controller (Admin, Manager, Staff, Report, Auth, dsb)
│   ├── Models/             # Model Eloquent (Product, Category, StockTransaction, User, Supplier)
│   └── Exports/Imports/    # Export/Import Excel
├── resources/
│   ├── views/              # Blade templates (admin, manajergudang, staff, auth, dsb)
│   └── js/css/             # Asset frontend
├── routes/
│   └── web.php             # Routing aplikasi
├── database/
│   ├── migrations/         # Struktur tabel
│   └── seeders/            # Data awal (dummy)
└── public/                 # Asset publik
```

---

## ⚡ Instalasi Cepat

### 1. Clone & Masuk ke Folder

```bash
git clone https://github.com/GeishaMagangJogja/StockifyApp.git
cd StockifyApp
```

### 2. Install Dependency

```bash
composer install
npm install && npm run build
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```
Edit `.env` dan sesuaikan database:
```
DB_DATABASE=stockify
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrasi & Seed Database

```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Aplikasi

```bash
php artisan serve
```
Buka [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 👤 Role & Login Default

| Role           | Email                | Password  |
|----------------|----------------------|-----------|
| Admin          | admin@stockify.com   | password  |
| Manajer Gudang | manager@stockify.com | password  |
| Staff Gudang   | staff@stockify.com   | password  |

---

## 📊 Fitur Laporan

- **Laporan Stok**: Lihat stok aman, menipis, dan habis.
- **Laporan Transaksi**: Filter berdasarkan tanggal, jenis (masuk/keluar), produk, dsb.
- **Export Excel**: Semua laporan bisa diekspor ke Excel.
- **Statistik**: Grafik barang masuk/keluar mingguan.

---

## 🔒 Keamanan

- Sistem login & otorisasi berbasis role.
- Validasi input & proteksi CSRF.
- Audit log aktivitas penting.

---

## 🧑‍💻 Kontribusi

1. Fork repo ini
2. Buat branch baru: `git checkout -b fitur-baru`
3. Commit perubahan: `git commit -m "fitur baru"`
4. Push ke repo kamu
5. Buat Pull Request

---

## 📝 Tips Pengembangan

- Gunakan `php artisan optimize:clear` jika ada error cache.
- Untuk generate model + migration + controller:
  ```bash
  php artisan make:model NamaModel -mcr
  ```
- Jalankan test:
  ```bash
  php artisan test
  ```

---

## 📄 Lisensi

MIT License © 2025 Stokyfiy

---

**Stockify** - Solusi stok gudang modern, mudah, dan powerful!


