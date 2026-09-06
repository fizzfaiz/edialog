# e-Dialog Prestasi

Sistem Pengurusan Dialog Prestasi Pendidikan — platform berasaskan web untuk mendigitalkan proses **Dialog Prestasi** di peringkat **KPM**, **JPN**, dan **PPD**.

## Ciri Utama

- Rakaman, pemantauan dan pelaporan **Dialog Prestasi** secara berpusat.
- Modul teras **Dialog Prestasi (DP-PPD)** dengan autosimpan, kehadiran, isu & tindakan.
- **Maklum balas** dan pemantauan status isu (Selesai / Dalam Progress / Belum Selesai).
- **Papan pemuka** analitik mengikut kategori dan status dialog.
- **Kawalan akses** berasaskan peranan (RBAC) — Super Admin, KPM, JPN Admin/User, PPD Admin/User, Sektor Admin.
- Penjanaan laporan **PDF**.
- Tetapan antara muka peribadi (font & saiz font).

## Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.2+, Laravel 11 |
| Pangkalan Data | MySQL (ujian: SQLite in-memory) |
| Frontend | Blade, Bootstrap 5, jQuery, DataTables |
| Kebenaran | Spatie Laravel Permission (RBAC) |
| PDF | barryvdh/laravel-dompdf |

## Keperluan

- PHP 8.2 atau ke atas
- MySQL / MariaDB
- Composer
- Node.js & npm

## Pemasangan

1. Klon projek dan masuk ke direktori projek.
2. Salin `.env.example` ke `.env` dan konfigurasikan kelayakan pangkalan data:
   ```bash
   cp .env.example .env
   ```
3. Pasang kebergantungan PHP:
   ```bash
   composer install
   ```
4. Jana kunci aplikasi:
   ```bash
   php artisan key:generate --ansi
   ```
5. Jalankan migrasi dan seed data domain:
   ```bash
   php artisan migrate:fresh --ansi
   php artisan db:seed --class=UserRolePermissionSeeder --ansi
   ```
6. Pasang dan bina aset frontend:
   ```bash
   npm install
   npm run build
   ```
7. Mulakan pelayan (atau gunakan Laragon/Nginx/Apache):
   ```bash
   php artisan serve
   ```
8. Akses di `http://edialog.test` (Laragon) atau `http://127.0.0.1:8000`.

## Akaun Seeded (UserRolePermissionSeeder)

Kata laluan standard bagi semua akaun: **`password123`**

| E-mel | Peranan | Pejabat |
|-------|---------|---------|
| `admin_jpn@example.com` | JPN Admin | JPN Melaka |
| `jpn_user@example.com` | JPN User | JPN Melaka |
| `admin_ppdmt@example.com` | PPD Admin | PPD Melaka Tengah |
| `ppd_user_mt@example.com` | PPD User | PPD Melaka Tengah |
| `admin_ppdag@example.com` | PPD Admin | PPD Alor Gajah |
| `ppd_user_ag@example.com` | PPD User | PPD Alor Gajah |
| `admin_ppdjs@example.com` | PPD Admin | PPD Jasin |
| `ppd_user_js@example.com` | PPD User | PPD Jasin |

## Ujian

Ujian dijalankan terhadap pangkalan data **SQLite in-memory** (tidak menyentuh pangkalan data sebenar):

```bash
php artisan test
```

## Peranan & Skop Capaian

| Peranan | Skop Capaian |
|---------|--------------|
| Super Admin | Semua pejabat dan semua modul |
| KPM | Keseluruhan data Dialog Prestasi |
| JPN | PPD di bawah negeri (anak) + pejabat JPN sendiri |
| PPD | Pejabat sendiri (sektor & unit) |

Hierarki pejabat: **induk → anak** (contoh: JPN → PPD).
