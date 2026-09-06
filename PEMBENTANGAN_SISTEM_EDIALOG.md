# e-Dialog Prestasi
## Sistem Pengurusan Dialog Prestasi Pendidikan

Pembentangan Sistem | KPM / JPN / PPD

---

# Agenda

1. Pengenalan Sistem
2. Objektif
3. Seni Bina & Teknologi
4. Peranan Pengguna & Hierarki Organisasi
5. Penerangan Komprehensif Setiap Modul
6. Aliran Kerja (Workflow)
7. Papan Pemuka (Dashboard)
8. Keselamatan & Kawalan Akses
9. Kesimpulan & Cadangan

---

# 1. Pengenalan

**e-Dialog Prestasi** ialah sistem berasaskan web untuk mendigitalkan proses
**Dialog Prestasi** di peringkat:

- **KPM** (Kementerian Pendidikan Malaysia)
- **JPN** (Jabatan Pendidikan Negeri)
- **PPD** (Pejabat Pendidikan Daerah)

Sistem ini menggantikan rekod manual dengan platform berpusat yang
membolehkan rakaman, pemantauan dan pelaporan prestasi secara digital.

---

# 2. Objektif

- Mendigitalkan rekod **Dialog Prestasi** secara berpusat
- Memudahkan **rakaman isu, fokus, dan tindakan**
- Menyokong **maklum balas** dan pemantauan status isu
- Menyediakan **kawalan akses** mengikut peranan (KPM / JPN / PPD)
- Menjana **laporan PDF** dan **papan pemuka** analitik
- Mengurangkan kerja manual dan kehilangan data

---

# 3. Seni Bina & Teknologi

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP 8.2, Laravel 11 |
| Pangkalan Data | MySQL |
| Frontend | Blade, Bootstrap, JavaScript, jQuery, DataTables |
| Kebenaran | Spatie Laravel Permission (RBAC) |
| PDF | barryvdh/laravel-dompdf |
| Antara Muka | Bahasa Melayu |

**Seni bina:** MVC (Model-View-Controller) mengikut standard Laravel.

---

# 4. Peranan Pengguna & Hierarki Organisasi

| Peranan | Skop Capaian |
|---------|--------------|
| **Super Admin** | Semua pejabat dan semua modul |
| **KPM** | Keseluruhan data Dialog Prestasi |
| **JPN** | PPD di bawah negeri (anak) |
| **PPD** | Pejabat sendiri (sektor & unit) |

Hierarki pejabat: **induk → anak** (contoh: JPN → PPD).

Setiap pengguna boleh dihubungkan kepada **Pejabat Pendidikan**, **Sektor**,
dan **Unit** yang menentukan skop data yang boleh diakses.

---

# 5. Penerangan Komprehensif Setiap Modul

Sistem ini terdiri daripada **6 modul utama** seperti berikut.

---

## 5.1 Modul Papan Pemuka (Dashboard)

**Ringkasan:** Halaman utama selepas log masuk yang memaparkan status
keseluruhan Dialog Prestasi secara visual.

**Ciri-ciri:**
- Statistik isu dikumpulkan **mengikut kategori** dialog:
  - Dialog Prestasi Negeri
  - Dialog Prestasi Berfokus Negeri
  - Dialog Prestasi Berfokus Daerah
  - Dialog Prestasi PPD
  - Dialog Prestasi Daerah
  - Dialog Prestasi Mingguan Daerah
- Paparan jumlah isu mengikut **status**:
  - Selesai
  - Dalam Progress
  - Belum Selesai
- Ikon visual bagi setiap kategori untuk rujukan pantas.

**Kepentingan:** Memberi gambaran menyeluruh kepada pihak pengurusan tentang
prestasi dan status tindakan dalam satu paparan.

---

## 5.2 Modul Dialog Prestasi (DP-PPD)

**Ringkasan:** Modul teras untuk merekod dan mengurus laporan Dialog Prestasi.

**Ciri-ciri:**
- **Cipta laporan** dengan maklumat asas:
  - Tarikh, Hari (auto-kira), Masa, Tempat
  - Kategori dialog
  - Pengerusi
- **Autosimpan** — setiap perubahan disimpan secara automatik tanpa perlu klik simpan.
- **Kehadiran** — senarai peserta beserta nama dan jawatan.
- **Isu & Tindakan** — setiap isu mengandungi:
  - Fokus
  - Isu
  - Tindakan
  - Sektor/Unit yang ditag
  - Sektor/Pegawai yang bertanggungjawab
- **Pengesahan** — maklumat dicatat oleh dan disahkan oleh.
- **Senarai laporan** — dengan carian, tapisan tarikh, dan susunan (DataTables).
- **Tindakan bagi setiap laporan:**
  - Lihat (show)
  - Edit
  - Maklum Balas (feedback)
  - Cetak PDF
  - Padam (hanya KPM/Super Admin)

**Kepentingan:** Merupakan nadi sistem yang mendigitalkan rekod mesyuarat
Dialog Prestasi daripada borang manual kepada pangkalan data berpusat.

---

## 5.3 Modul Maklum Balas (Feedback)

**Ringkasan:** Membolehkan pihak berkaitan memberi maklum balas terhadap
setiap isu yang direkodkan.

**Ciri-ciri:**
- Setiap isu boleh diberikan **jawapan** dan **status**:
  - Selesai
  - Dalam Progress
  - Belum Selesai
- Rekod **pihak yang menjawab** (answered_by) secara automatik.
- Kawalan akses berdasarkan tag sektor/unit:
  - Pengguna hanya boleh menjawab isu yang ditag kepada sektor/unit mereka.
  - JPN dan KPM boleh menjawab semua isu dalam skop masing-masing.

**Kepentingan:** Menyokong pemantauan pelaksanaan tindakan dan akauntabiliti.

---

## 5.4 Modul Pengurusan Pengguna (Users)

**Ringkasan:** Mengurus akaun pengguna sistem dan peranan yang dipegang.

**Ciri-ciri:**
- **Senarai pengguna** dengan paginasi.
- **Tambah pengguna** dengan:
  - Nama, emel, kata laluan
  - Penetapan **Peranan** (Role)
  - Penetapan **Pejabat Pendidikan** (KPM/JPN/PPD)
- **Edit pengguna** — kemas kini maklumat, peranan dan pejabat.
- **Padam pengguna** dengan perlindungan:
  - Super Admin tidak boleh dipadam.
  - Pengguna tidak boleh memadam akaun sendiri.
- Penyulitan kata laluan menggunakan **Hash** (bcrypt).

**Kepentingan:** Menjamin setiap pengguna memiliki identiti dan peranan yang
tepat untuk mengakses sistem.

---

## 5.5 Modul Pengurusan Peranan & Kebenaran (Roles & Permissions)

**Ringkasan:** Mengurus peranan dan kebenaran (RBAC) sistem.

**Ciri-ciri:**
- **Senarai peranan** beserta kebenaran yang dipegang.
- **Tambah peranan** dan pilih kebenaran yang berkaitan.
- **Edit peranan** — kemas kini nama dan kebenaran.
- **Padam peranan** dengan perlindungan:
  - Peranan `Super Admin` tidak boleh diedit atau dipadam.
  - Peranan yang sedang digunakan oleh pengguna semasa tidak boleh dipadam.
- **Kebenaran modular** dijana berdasarkan modul dan tindakan:
  - Contoh: `view-dp-ppd`, `create-dp-ppd`, `edit-dp-ppd`, `delete-dp-ppd`, `feedback-dp-ppd`
  - Modul lain: peranan, pengguna, produk, tetapan.

**Kepentingan:** Menyediakan kawalan akses halus yang fleksibel mengikut
keperluan organisasi tanpa perlu menulis kod baharu.

---

## 5.6 Modul Tetapan Sistem (Settings)

**Ringkasan:** Tetapan peribadi antara muka pengguna.

**Ciri-ciri:**
- Pemilihan **jenis font**:
  - Inter, Nunito, System UI, Arial
- Pemilihan **saiz font**:
  - Kecil, Sederhana, Besar
- Tetapan disimpan **per pengguna** (setiap pengguna ada pilihan sendiri).

**Kepentingan:** Meningkatkan keselesaan dan kebolehcapaian pengguna.

---

## 5.7 Modul Tambahan: Produk (Products)

**Ringkasan:** Modul sokongan untuk mengurus data produk (sesuai untuk
demonstrasi atau keperluan tambahan).

**Ciri-ciri:**
- CRUD asas (Tambah, Lihat, Edit, Padam) produk.
- Terikat dengan sistem kebenaran (RBAC) yang sama.

**Kepentingan:** Menunjukkan kebolehlanjutan sistem — modul baharu boleh
ditambah dengan mudah menggunakan corak yang sama.

---

# 6. Aliran Kerja (Workflow)

1. Pengguna **log masuk** mengikut peranan
2. Cipta **Laporan Dialog Prestasi**
3. Isi maklumat mesyuarat (tarikh, masa, tempat, pengerusi)
4. Tambah **kehadiran**
5. Tambah **isu dan tindakan** (tag sektor/unit)
6. Sistem **autosimpan** setiap perubahan
7. **Pengesahan** dan **simpan** laporan
8. Pihak berkaitan beri **maklum balas**
9. **Pemantauan** status & jana **PDF**

---

# 7. Papan Pemuka (Dashboard)

Memaparkan statistik **mengikut kategori** Dialog Prestasi:

- Selesai
- Dalam Progress
- Belum Selesai

Contoh kategori: Dialog Prestasi Negeri, Daerah, PPD, Mingguan.

---

# 8. Keselamatan

- **Pengesahan** pengguna (auth)
- **Kebenaran** berlapis (middleware + policy)
- Skop data dihadkan mengikut **pejabat/negeri/daerah**
- Validasi input (Form Request)
- Hubungan pangkalan data dengan **kekangan kunci asing**

---

# 9. Kesimpulan

- e-Dialog Prestasi **mempercepatkan** proses rakaman & pemantauan
- Meningkatkan **ketelusan** dan **akauntabiliti**
- Skop akses yang **fleksibel** mengikut hierarki organisasi
- Modul yang **bersifat modular** dan mudah dikembangkan

---

# Terima Kasih

Soal Jawab & Perbincangan