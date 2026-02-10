# Tes Backend SQL — Aksamedia

Implementasi endpoint Laravel untuk menghitung dan menampilkan nilai siswa dari tabel `nilai` menggunakan **SQL murni**.

## 📌 Requirement

- **Laravel** (v11)
- **MySQL / MariaDB**
- **phpMyAdmin / Adminer / HeidiSQL**

## 🚀 Cara Instalasi

```bash
# 1. Clone repository
git clone https://github.com/WaySTN/sql_test_aksamedia.git
cd sql_test_aksamedia

# 2. Install dependencies
composer install

# 3. Copy .env
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=test_backend_nilai
# DB_USERNAME=root
# DB_PASSWORD=

# 5. Buat database & import data
mysql -u root -e "CREATE DATABASE IF NOT EXISTS test_backend_nilai;"
mysql -u root test_backend_nilai < nilai.sql
```

## 🧪 Cara Testing

Jalankan server:
```bash
php artisan serve
```

Buka di browser:
| Endpoint | URL |
|----------|-----|
| **Nilai RT** | `http://localhost:8000/nilaiRT` |
| **Nilai ST** | `http://localhost:8000/nilaiST` |

## 📂 Struktur File Utama

```
├── app/Http/Controllers/
│   └── NilaiController.php      # Controller utama (nilaiRT & nilaiST)
├── routes/
│   └── web.php                  # Route /nilaiRT dan /nilaiST
├── nilai.sql                    # Data SQL untuk di-import
├── 1.png                        # Referensi output nilaiRT
└── 2.png                        # Referensi output nilaiST
```

## 📝 Penjelasan Endpoint

### `GET /nilaiRT` — Nilai Tes Minat (RIASEC)

- Menggunakan `materi_uji_id = 7`
- **Tidak mengikutkan** Pelajaran Khusus (`pelajaran_id = 43`)
- Output per siswa: `nama`, `nisn`, `nilaiRt` (realistic, investigative, artistic, social, enterprising, conventional)

### `GET /nilaiST` — Nilai Tes Skolastik

- Menggunakan `materi_uji_id = 4`
- Setiap pelajaran dikalikan bobot:
  | Pelajaran | pelajaran_id | Bobot |
  |-----------|:------------:|:-----:|
  | Verbal | 44 | × 41.67 |
  | Kuantitatif | 45 | × 29.67 |
  | Penalaran | 46 | × 100 |
  | Figural | 47 | × 23.81 |
- Output per siswa: `nama`, `nisn`, `total`, `listNilai`
- Diurutkan dari **total nilai terbesar**

## ⚙️ Aturan yang Dipatuhi

- ✅ Perhitungan menggunakan **SQL murni** (`CASE WHEN` untuk bobot)
- ✅ Collection hanya digunakan untuk **grouping data terakhir**
- ✅ Penamaan variabel sesuai output gambar referensi
